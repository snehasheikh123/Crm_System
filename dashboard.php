<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
$name = $_SESSION['user_name'];
$role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CRM Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="dashboard">
    <h2>Welcome, <?php echo $name; ?> 👋</h2>
    <p>You are logged in as <strong><?php echo ucfirst($role); ?></strong></p>

    <div class="menu">
      <a href="customers/customers.php">📇 Customers</a>
      <a href="leads/leads.php">📋 Leads</a>
      <a href="reports/lead_report.php">📊 Lead Report</a>
      <a href="files.php">📁 Files</a>
      <a href="auth/logout.php" class="logout">🚪 Logout</a>
    </div>
  </div>
</body>
</html>
