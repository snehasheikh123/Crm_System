<?php
// index.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CRM Dashboard</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
  <div class="dashboard">
    <h1>Welcome to CRM Dashboard 👋</h1>
    <div class="card-container">
      <a href="customers.php" class="card">
        <h3>👥 Customers</h3>
        <p>Manage all customers</p>
      </a>

      <a href="leads.php" class="card">
        <h3>📋 Leads</h3>
        <p>Track and follow leads</p>
      </a>

      <a href="lead_report.php" class="card">
        <h3>📊 Lead Reports</h3>
        <p>View conversion analytics</p>
      </a>

      <a href="logout.php" class="card logout">
        <h3>🚪 Logout</h3>
        <p>End your session</p>
      </a>
    </div>
  </div>
</body>
</html>
