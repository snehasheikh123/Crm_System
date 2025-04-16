<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $company = mysqli_real_escape_string($conn, $_POST["company"]);
    $notes = mysqli_real_escape_string($conn, $_POST["notes"]);

    $sql = "INSERT INTO customers (name, email, phone, company) VALUES ('$name', '$email', '$phone', '$company')";
    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Customer added successfully!";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>➕ Add Customer</title>
  <link rel="stylesheet" href="../assets/css/add_customer.css">
</head>
<body>
  <div class="card">
    <h2>👤 Add New Customer</h2>

    <?php if ($msg != ""): ?>
      <p class="<?php echo strpos($msg, '✅') !== false ? 'success-msg' : 'error-msg'; ?>">
        <?php echo $msg; ?>
      </p>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="name" placeholder="👤 Full Name" required>
      <input type="email" name="email" placeholder="📧 Email">
      <input type="text" name="phone" placeholder="📱 Phone Number">
      <input type="text" name="company" placeholder="🏢 Company Name">
      <textarea name="notes" placeholder="📝 Notes (optional)"></textarea>
      <button type="submit">➕ Add Customer</button>
      <a href="../dashboard.php" class="back">⬅ Back to Dashboard</a>
      </form>
  </div>
</body>
</html>
