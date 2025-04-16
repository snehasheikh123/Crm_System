<?php
include("../db/db_connect.php");
session_start();

if (!isset($_GET['id']) || !isset($_GET['lead_id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);
$lead_id = intval($_GET['lead_id']);

mysqli_query($conn, "DELETE FROM followups WHERE id = $id");

header("Location: followup.php?lead_id=$lead_id");
exit;
