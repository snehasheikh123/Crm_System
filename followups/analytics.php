<?php
// analytics.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

// Get lead status counts
$status_counts = ["New" => 0, "In Progress" => 0, "Won" => 0, "Lost" => 0];
$result = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM leads GROUP BY status");
while ($row = mysqli_fetch_assoc($result)) {
    $status_counts[$row['status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analytics Report</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container">
    <h2>📊 Lead Conversion Report</h2>
    <canvas id="statusChart" width="400" height="200"></canvas>
    <a href="leads.php" class="back">🔙 Back to Leads</a>
  </div>

  <script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['New', 'In Progress', 'Won', 'Lost'],
        datasets: [{
          label: 'Number of Leads',
          data: [<?= $status_counts['New'] ?>, <?= $status_counts['In Progress'] ?>, <?= $status_counts['Won'] ?>, <?= $status_counts['Lost'] ?>],
          backgroundColor: ['#3498db', '#f1c40f', '#2ecc71', '#e74c3c']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'Lead Status Overview' }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });
  </script>
</body>
</html>