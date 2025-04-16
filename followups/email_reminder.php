<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

include("../db/db_connect.php");

// ✅ Include PHPMailer
require_once("../phpmailer/PHPMailer.php");
require_once("../phpmailer/SMTP.php");
require_once("../phpmailer/Exception.php");

if (!isset($_GET['lead_id'])) {
    die("Lead ID not provided.");
}

$lead_id = intval($_GET['lead_id']);

// ✅ Get Reminder History
$reminder_query = "SELECT * FROM email_reminders WHERE lead_id = $lead_id ORDER BY reminder_date DESC";
$reminder_result = mysqli_query($conn, $reminder_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Reminder</title>
    <link rel="stylesheet" href="../assets/css/email_reminder.css">
</head>
<body>

<h2>📩 Send Email Reminder</h2>
<p id="statusMessage"></p>

<div class="container">
    <form id="reminderForm">
        <label>Email To:</label>
        <input type="email" name="email_sent_to" placeholder="Client's Email" required>

        <label>Subject:</label>
        <input type="text" name="subject" placeholder="Reminder Subject" required>

        <label>Message:</label>
        <textarea name="message" rows="5" placeholder="Type your reminder message..." required></textarea>
        <label>Send At (Schedule Date & Time):</label>
<input type="datetime-local" name="send_at" required>

        <button type="submit">📧 Send Reminder</button>
        <a href="../leads/leads.php">🔙 Back</a>
    </form>

    <div class="history">
        <h3>📬 Reminder History</h3>
        <ul id="reminderHistory">
            <?php while ($row = mysqli_fetch_assoc($reminder_result)) { ?>
                <li>
                    <strong><?= date('d M Y, H:i', strtotime($row['reminder_date'])) ?>:</strong><br>
                    📨 <?= htmlspecialchars($row['email_sent_to']) ?><br>
                    📝 <em><?= htmlspecialchars($row['subject']) ?></em><br>
                    🗒️ <?= nl2br(htmlspecialchars($row['message'])) ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script>
document.querySelector("#reminderForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const leadId = <?= $lead_id ?>;

    fetch(`email_reminder_ajax.php?lead_id=${leadId}`, {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const statusEl = document.getElementById("statusMessage");
        statusEl.innerText = data.message;
        statusEl.style.color = data.success ? "green" : "red";

        if (data.success) {
            const historyList = document.getElementById("reminderHistory");
            const li = document.createElement("li");
            li.innerHTML = `
                <strong>${data.reminder_date}:</strong><br>
                📨 ${data.email}<br>
                📝 <em>${data.subject}</em><br>
                🗒️ ${data.message.replace(/\n/g, "<br>")}
            `;
            historyList.prepend(li);
            form.reset();
        }
    })
    .catch(err => {
        document.getElementById("statusMessage").innerText = "❌ Server error.";
    });
});
</script>

</body>
</html>
