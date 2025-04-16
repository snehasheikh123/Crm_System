<?php
include("../db/db_connect.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lead_id = $_POST['lead_id'];
    $email_sent_to = $_POST['email_sent_to'];
    $subject = $_POST['subject'];
    $message_text = $_POST['message'];
    $send_at = $_POST['send_at'];

    $stmt = $conn->prepare("INSERT INTO email_reminders (lead_id, email_sent_to, subject, message, send_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $lead_id, $email_sent_to, $subject, $message_text, $send_at);

    if ($stmt->execute()) {
        $message = "✅ Reminder added successfully!";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Email Reminder</title>
    <link rel="stylesheet" href="../assets/css/add_reminder.css">

</head>
<body>
    <h2>Add Email Reminder</h2>

    <?php if ($message != "") { echo "<p>$message</p>"; } ?>

    <form method="POST">
        <label>Lead ID:</label><br>
        <input type="number" name="lead_id" required><br><br>

        <label>Email To:</label><br>
        <input type="email" name="email_sent_to" required><br><br>

        <label>Subject:</label><br>
        <input type="text" name="subject" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="5" cols="40" required></textarea><br><br>

        <label>Send At (Date & Time):</label><br>
        <input type="datetime-local" name="send_at" required><br><br>

        <input type="submit" value="Add Reminder">
        <a href="../admin/reminder.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Manage Reminder</a>

    </form>
</body>
</html>
