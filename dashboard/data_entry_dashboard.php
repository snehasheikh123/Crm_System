<?php
session_start();
if (!isset($_SESSION['user_id']) ||$_SESSION['user_role'] !== 'data_entry') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>🧾 Data Entry Operator Dashboard</title>
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">

    <h2>Welcome 🧾 Data Entry Operator Dashboard, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p>  Add new leads to the system
         Upload documents and files
         Maintain lead and document records
</p>
</div>
</body>
</html>
