<?php
include("../db/db_connect.php");
$password = password_hash("admin123", PASSWORD_DEFAULT);
$sql = "INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@example.com', '$password', 'admin')";
if(mysqli_query($conn, $sql)){
    echo "User created successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
