<?php
// edit_customer.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

if (!isset($_GET['id'])) {
    echo "Invalid Request!";
    exit;
}

$id = intval($_GET['id']);
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $company = mysqli_real_escape_string($conn, $_POST["company"]);

    $sql = "UPDATE customers SET name='$name', email='$email', phone='$phone', company='$company' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Customer updated successfully!";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}

$customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM customers WHERE id=$id"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Customer</title>
<link rel="stylesheet" href="../assets/css/edit_customer.css">
</head>
<body>


  <div class="main-container">
    <div class="card">
      <h2><i class="fas fa-user-edit"></i> Edit Customer</h2>

      <?php if ($msg != ""): ?>
        <p class="<?= strpos($msg, 'success') !== false ? 'success-msg' : 'error-msg' ?>">
          <?= $msg ?>
        </p>
      <?php endif; ?>

      <form method="post">
        <input type="text" name="name" value="<?= htmlspecialchars($customer['name']) ?>" required placeholder="Name">
        <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" placeholder="Email">
        <input type="tel" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" placeholder="Phone">
        <input type="text" name="company" value="<?= htmlspecialchars($customer['company']) ?>" placeholder="Company">
        
        <button type="submit"><i class="fas fa-save"></i> Update</button>
        <a href="customers.php" class="back"><i class="fas fa-arrow-left"></i> Back</a>
      </form>
    </div>
  </div>
</body>

</html>
