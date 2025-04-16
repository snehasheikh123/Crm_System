<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
include("../db/db_connect.php");

// Filter logic
$filter = $_GET['filter'] ?? 'today';
$dateCondition = "";

if ($filter === 'week') {
    $dateCondition = "WHERE YEARWEEK(lead_date, 1) = YEARWEEK(CURDATE(), 1)";
} elseif ($filter === 'month') {
    $dateCondition = "WHERE MONTH(lead_date) = MONTH(CURDATE()) AND YEAR(lead_date) = YEAR(CURDATE())";
} else {
    $dateCondition = "WHERE DATE(lead_date) = CURDATE()";
}

// Dashboard data
$today = date('Y-m-d');
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$totalLeads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM leads"))['total'];
$todayFollowups = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM followups WHERE DATE(followup_date) = '$today'"))['total'];
$convertedLeads = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM leads WHERE status = 'converted'"))['total'];
$conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

// Pie chart data for lead statuses
$statusQuery = mysqli_query($conn, "SELECT status, COUNT(*) AS count FROM leads GROUP BY status");
$leadStatus = [];
$leadCounts = [];
while ($row = mysqli_fetch_assoc($statusQuery)) {
    $leadStatus[] = $row['status'];
    $leadCounts[] = $row['count'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; display: flex; }
        .main-content { flex-grow: 1; padding: 20px; }
        .dashboard { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .card { flex: 1; min-width: 200px; padding: 20px; background: #f8f8f8; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .chart-container, .pie-chart-container { margin-top: 40px; background: #fff; padding: 20px; border-radius: 10px; max-width: 800px; }
        .filter-export { margin-top: 20px; display: flex; gap: 15px; align-items: center; }
    </style>
</head>
<body>

<?php include('../sidebar.php'); ?>

<div class="main-content">
    <h2>Welcome Admin, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

    <div class="filter-export">
        <label>Filter Leads By:
            <select onchange="location = '?filter=' + this.value">
                <option value="today" <?= $filter === 'today' ? 'selected' : '' ?>>Today</option>
                <option value="week" <?= $filter === 'week' ? 'selected' : '' ?>>This Week</option>
                <option value="month" <?= $filter === 'month' ? 'selected' : '' ?>>This Month</option>
            </select>
        </label>
        <button onclick="exportToExcel()">Export to Excel</button>
        <button onclick="exportToPDF()">Export to PDF</button>
    </div>

    <div class="dashboard">
        <div class="card"><h3>Total Users</h3><p><?= $totalUsers ?></p></div>
        <div class="card"><h3>Total Leads</h3><p><?= $totalLeads ?></p></div>
        <div class="card"><h3>Follow-ups Today</h3><p><?= $todayFollowups ?></p></div>
        <div class="card"><h3>Conversion Rate</h3><p><?= $conversionRate ?>%</p></div>
    </div>

    <div class="chart-container">
        <canvas id="dashboardChart" width="600" height="300"></canvas>
    </div>

    <div class="pie-chart-container">
        <canvas id="leadPieChart" width="400" height="300"></canvas>
    </div>
</div>

<script>
// Bar Chart
new Chart(document.getElementById('dashboardChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Users', 'Leads', 'Follow-ups Today', 'Conversion %'],
        datasets: [{
            label: 'Dashboard Overview',
            data: [<?= $totalUsers ?>, <?= $totalLeads ?>, <?= $todayFollowups ?>, <?= $conversionRate ?>],
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, title: { display: true, text: 'Real-time Admin Dashboard Stats' } },
        scales: { y: { beginAtZero: true } }
    }
});

// Pie Chart for Lead Status
new Chart(document.getElementById('leadPieChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($leadStatus) ?>,
        datasets: [{
            label: 'Lead Status',
            data: <?= json_encode($leadCounts) ?>,
            backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#007bff', '#17a2b8', '#6f42c1']
        }]
    },
    options: {
        responsive: true,
        plugins: { title: { display: true, text: 'Lead Status Overview' } }
    }
});

// Export to Excel
function exportToExcel() {
    const wb = XLSX.utils.book_new();
    const wsData = [
        ['Total Users', <?= $totalUsers ?>],
        ['Total Leads', <?= $totalLeads ?>],
        ['Follow-ups Today', <?= $todayFollowups ?>],
        ['Conversion Rate (%)', <?= $conversionRate ?>]
    ];
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, 'Dashboard Stats');
    XLSX.writeFile(wb, 'dashboard_data.xlsx');
}

// Export to PDF
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(16);
    doc.text("Dashboard Report", 20, 20);

    doc.setFontSize(12);
    doc.text("Total Users: <?= $totalUsers ?>", 20, 40);
    doc.text("Total Leads: <?= $totalLeads ?>", 20, 50);
    doc.text("Follow-ups Today: <?= $todayFollowups ?>", 20, 60);
    doc.text("Conversion Rate: <?= $conversionRate ?>%", 20, 70);

    doc.save("dashboard_report.pdf");
}
</script>

</body>
</html>
