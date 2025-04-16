<?php
session_start();
include "../db/db_connect.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // updated to 'role'
        $_SESSION['username'] = $user['name']; // consistent name for display

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: ../dashboard/admin_dashboard.php");
                break;
            case 'sales':
                header("Location: ../sales_dashboard.php");
                break;
            case 'support':
                header("Location: ../support_dashboard.php");
                break;
            case 'marketing':
                header("Location: ../marketing_dashboard.php");
                break;
            case 'data_entry':
                header("Location: ../data_entry_dashboard.php");
                break;
            case 'customer':
                header("Location: ../customer_dashboard.php");
                break;
            default:
                $error = "⚠️ Invalid role.";
        }
        exit;
    } else {
        $error = "❌ Invalid email or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CRM Login</title>
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
  <div class="login-container">
    <h2>🔐 CRM Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="📧 Email" required />
      <input type="password" name="password" placeholder="🔑 Password" required />
      <button type="submit">➡️ Login</button>
      <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
