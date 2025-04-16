<?php
session_start();
if (!isset($_SESSION['user_id']) ||$_SESSION['role'] !== 'customer') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

    <title>Customer Dashboard</title>
</head>
<body>
<?php include("sidebar.php"); ?>
<div class="main-content">

    <h2>Welcome Dear, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p> Here you can  View and update  your profile
        and Raise support tickets
        and Submit product/service feedback
</p>
</div>
</body>
</html>
