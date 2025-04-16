<?php
// files.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include "db/db_connect.php";

// File Upload Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $file = $_FILES['file'];

    if ($file['error'] == 0) {
        $uploadDir = "assets/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $stmt = $conn->prepare("INSERT INTO customer_files (customer_id, file_name) VALUES (?, ?)");
            $stmt->bind_param("is", $customer_id, $fileName);
            $stmt->execute();
            $success = "File uploaded successfully!";
        } else {
            $error = "Failed to move uploaded file.";
        }
    } else {
        $error = "File upload error.";
    }
}

// Fetch customers for dropdown
$customers = mysqli_query($conn, "SELECT id, name FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📁 Upload Files</title>
  <link rel="stylesheet" href="assets/css/upload.css">
</head>
<body>
  <div class="container">
    <h2>🗂 Upload File for Customer</h2>

    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
      <label>Select Customer:</label>
      <select name="customer_id" required>
        <option value="">-- Choose Customer --</option>
        <?php while ($c = mysqli_fetch_assoc($customers)) {
            echo "<option value='{$c['id']}'>{$c['name']}</option>";
        } ?>
      </select><br><br>

      <label>Select File:</label>
      <input type="file" name="file" required><br><br>

      <button type="submit">📤 Upload File</button>
    </form>

    <h3>📂 Uploaded Files</h3>
    <table border="1" cellpadding="8">
      <tr>
        <th>Customer</th>
        <th>File Name</th>
        <th>Uploaded On</th>
        <th>Download</th>
        <th>Delete</th>
      </tr>
      <?php
      $query = "SELECT f.*, c.name AS customer_name FROM customer_files f 
                JOIN customers c ON f.customer_id = c.id 
                ORDER BY f.uploaded_on DESC";
      $result = mysqli_query($conn, $query);
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['customer_name']}</td>
                <td>{$row['file_name']}</td>
                <td>{$row['uploaded_on']}</td>
                <td><a href='assets/uploads/{$row['file_name']}' download>⬇️</a></td>
                <td><a href='delete_file.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>❌</a></td>
              </tr>";
      }
      ?>
    </table>

    <br><a href="customers/customers.php">🔙 Back to Customers</a>
  </div>
</body>
</html>
