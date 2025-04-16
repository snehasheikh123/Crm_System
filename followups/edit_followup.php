<?php
include("../db/db_connect.php");
session_start();

if (!isset($_GET['id']) || !isset($_GET['lead_id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);
$lead_id = intval($_GET['lead_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $stmt = $conn->prepare("UPDATE followups SET note = ? WHERE id = ?");
    $stmt->bind_param("si", $note, $id);
    $stmt->execute();
    header("Location: followup.php?lead_id=$lead_id");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM followups WHERE id = $id");
$row = mysqli_fetch_assoc($result);
?>

<form method="post">
  <textarea name="note" rows="4" required><?= htmlspecialchars($row['note']) ?></textarea>
  <button type="submit">Update Follow-up</button>
</form>
