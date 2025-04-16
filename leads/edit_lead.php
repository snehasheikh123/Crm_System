<?php
// edit_lead.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

if (!isset($_GET['id'])) {
    die("Lead ID not provided.");
}

$lead_id = intval($_GET['id']);

// Fetch lead details
$sql = "SELECT * FROM leads WHERE id = $lead_id";
$result = mysqli_query($conn, $sql);
$lead = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead_title = $_POST['lead_title'];
    $status = $_POST['status'];
    $next_followup = $_POST['next_followup'];

    $update_sql = "UPDATE leads SET lead_title='$lead_title', status='$status', next_followup='$next_followup' WHERE id=$lead_id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: leads.php");
        exit;
    } else {
        echo "Error updating lead.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Lead</title>
  <link rel="stylesheet" href="../assets/css/edit_lead.css">
  </head>
<body>
  <div class="container">
    <h2>✏️ Edit Lead</h2>
    <form method="post">
      <label>Lead Title</label>
      <input type="text" name="lead_title" value="<?= htmlspecialchars($lead['lead_title']) ?>" required>

      <label>Status</label>
      <select name="status">
        <option value="New" <?= $lead['status'] === 'New' ? 'selected' : '' ?>>New</option>
        <option value="In Progress" <?= $lead['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="Won" <?= $lead['status'] === 'Won' ? 'selected' : '' ?>>Won</option>
        <option value="Lost" <?= $lead['status'] === 'Lost' ? 'selected' : '' ?>>Lost</option>
      </select>

      <label>Next Follow-up</label>
      <input type="date" name="next_followup" value="<?= $lead['next_followup'] ?>">

      <button type="submit">💾 Update Lead</button>
      <a href="leads.php" class="back">🔙 Back</a>
    </form>
  </div>
</body>
</html>