<?php
// delete_customer.php
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

$sql = "DELETE FROM customers WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    header("Location: customers.php?msg=deleted");
} else {
    echo "❌ Error deleting record: " . mysqli_error($conn);
}
?>