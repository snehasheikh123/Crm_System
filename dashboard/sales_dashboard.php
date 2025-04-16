<?php
session_start();
if (!isset($_SESSION['user_id']) ||$_SESSION['user_role'] !== 'sales') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>💼 Sales Executive Dashboard</title>
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">

<h2>💼 Sales Executive Dashboard <?php echo $_SESSION['user_name']; ?>!</h2>
<p>Here you can manage customers, leads, reports, and files.
        ✔ Add and manage leads
        ✔ Schedule follow-ups
        ✔ Update deal status
        ✔ View customer informantion

        </p>
</div>
</body>
</html>

