<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'marketing') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📢 Marketing Team Dashboard</title>
</head>
<body>
  <?php include 'sidebar.php'; ?>
  <div class="main-content">

  <h2>Welcome Marketing team, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p>Here you can manage marketing campaigns, track performance, and analyze customer data.</p>
    <!-- <h2>📢 Marketing Team Dashboard</h2> -->
    <p>✔ Launch email campaigns
        ✔ Promote offers & discounts
        ✔ Track performance of campaigns</p>
  </div>
</body>
</html>
