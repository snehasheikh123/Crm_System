<?php
// add_lead.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

$customers = mysqli_query($conn, "SELECT id, name FROM customers");
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = intval($_POST["customer_id"]);
    $lead_title = mysqli_real_escape_string($conn, $_POST["lead_title"]);
    $status = $_POST["status"];
    $next_followup = $_POST["next_followup"];

    $sql = "INSERT INTO leads (customer_id, lead_title, status, next_followup) 
            VALUES ('$customer_id', '$lead_title', '$status', '$next_followup')";
    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Lead added successfully!";
    } else {
        $msg = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Lead</title>
  <link rel="stylesheet" href="../assets/css/add_lead.css">
  </head>
<body>
  <div class="form-box">
    <h2>Add New Lead</h2>
    <?php if ($msg != "") echo "<p class='message'>$msg</p>"; ?>
    <form method="post">
      <label>Select Customer</label>
      <select name="customer_id" required>
        <option value="">-- Select --</option>
        <?php while ($row = mysqli_fetch_assoc($customers)) { ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php } ?>
      </select>
      <input type="text" name="lead_title" placeholder="Lead Title" required>
      <label>Status</label>
      <select name="status">
        <option value="New">New</option>
        <option value="In Progress">In Progress</option>
        <option value="Won">Won</option>
        <option value="Lost">Lost</option>
      </select>
      <label>Next Follow-up Date</label>
      <input type="date" name="next_followup">
      <button type="submit">➕ Add Lead</button>
      <a href="leads.php" class="back">📋 View Leads</a>
    </form>
  </div>
</body>
</html>
