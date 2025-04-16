<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit;
}

include("../db/db_connect.php");
require_once("../phpmailer/PHPMailer.php");
require_once("../phpmailer/SMTP.php");
require_once("../phpmailer/Exception.php");

if (!isset($_GET['lead_id'])) {
    echo json_encode(["success" => false, "message" => "Lead ID missing."]);
    exit;
}

$lead_id = intval($_GET['lead_id']);
$email   = $_POST['email_sent_to'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';
$send_at = $_POST['send_at'] ?? ''; // format: yyyy-mm-ddThh:mm (from input type="datetime-local")

// ✅ If send_at is in the future, store it for scheduled cron job
if (!empty($send_at) && strtotime($send_at) > time()) {
    $stmt = $conn->prepare("INSERT INTO email_reminders (lead_id, email_sent_to, subject, message, send_at, sent_status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issss", $lead_id, $email, $subject, $message, $send_at);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "✅ Reminder scheduled successfully for $send_at.",
        "scheduled" => true,
        "email" => $email,
        "subject" => $subject,
        "send_at" => $send_at
    ]);
    exit;
}

// ✅ If send_at is now or not provided, send email immediately
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'your-email@gmail.com';      // ✅ Replace
    $mail->Password   = 'your-app-password';         // ✅ Replace with App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('your-email@gmail.com', 'CRM Reminder');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = nl2br($message);

    $mail->send();

    $now = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO email_reminders (lead_id, email_sent_to, subject, message, send_at, sent_status) VALUES (?, ?, ?, ?, ?, 'sent')");
    $stmt->bind_param("issss", $lead_id, $email, $subject, $message, $now);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "✅ Reminder sent successfully.",
        "email" => $email,
        "subject" => $subject,
        "message" => $message,
        "reminder_date" => date('d M Y, H:i')
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "❌ Email failed: " . $mail->ErrorInfo
    ]);
}
