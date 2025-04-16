<?php
// lead_report.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

include("../db/db_connect.php");

// Get leads with customer name
$query = "
    SELECT leads.id, leads.lead_title, leads.status, leads.next_followup, customers.name AS customer_name
    FROM leads
    JOIN customers ON leads.customer_id = customers.id
    ORDER BY leads.status, leads.next_followup
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lead Report</title>
  <link rel="stylesheet" href="../assets/css/lead_report.css">
</head>
<body>
  <div class="report-container">
    <h2>📊 Lead Report</h2>
    <table>
      <thead>
        <tr>
          <th>Lead Title</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Next Follow-up</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= htmlspecialchars($row['lead_title']) ?></td>
            <td><?= htmlspecialchars($row['customer_name']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['next_followup']) ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <a href="../leads/leads.php" class="back-link">← Back to Leads</a>
    </div>
</body>
</html>
