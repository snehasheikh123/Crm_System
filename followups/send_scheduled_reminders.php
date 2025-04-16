<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("db/db_connect.php");
require_once("phpmailer/PHPMailer.php");
require_once("phpmailer/SMTP.php");
require_once("phpmailer/Exception.php");

$now = date('Y-m-d H:i:s');

// Fetch reminders due for sending
$sql = "SELECT * FROM email_reminders WHERE sent_status = 'pending' AND send_at <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $now);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';
        $mail->Password   = 'your-app-password';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('your-email@gmail.com', 'CRM Reminder');
        $mail->addAddress($row['email_sent_to']);

        $mail->isHTML(true);
        $mail->Subject = $row['subject'];
        $mail->Body    = nl2br($row['message']);

        $mail->send();

        // Update status to 'sent'
        $update = $conn->prepare("UPDATE email_reminders SET sent_status = 'sent' WHERE id = ?");
        $update->bind_param("i", $row['id']);
        $update->execute();

    } catch (Exception $e) {
        error_log("Failed to send reminder ID {$row['id']}: " . $mail->ErrorInfo);
    }
}
