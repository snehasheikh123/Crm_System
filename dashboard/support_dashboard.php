<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'support') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8"> -->
    <title>Support Dashboard</title>
    <!-- <link rel="stylesheet" href="../assets/css/sidebar.css"> -->
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <h2>🎧 Support Staff Dashboard</h2>
    <p>Welcome Support Staff, <?php echo $_SESSION['user_name']; ?>!</p>
    <p>Here you can manage customer support tickets, resolve issues, and collect feedback.</p>
        
    
</div>
</body>
</html>
