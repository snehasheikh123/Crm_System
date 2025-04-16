<?php
session_start();
include("../db/db_connect.php");

$result = $conn->query("SELECT * FROM email_reminders ORDER BY send_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Email Reminders</title>
    <link rel="stylesheet" href="../assets/css/reminder_list.css">
</head>
<body>
<?php include('../sidebar.php'); ?>

<div class="container">
    <h2><i class="fas fa-envelope"></i> Email Reminders</h2>
    <div class="search-box">
      <input type="text" id="searchInput" placeholder="🔍 Search by name, email, phone or company...">
    </div>

    <!-- Add New Reminder Button -->
    <a href="../admin/admin_add_reminder.php" class="add-btn">Add New Reminder</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lead ID</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Send At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['lead_id'] ?></td>
                    <td><?= $row['email_sent_to'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['message'] ?></td>
                    <td><?= $row['send_at'] ?></td>
                    <td class="<?= $row['sent_status'] == 'Sent' ? 'status-sent' : 'status-pending' ?>">
                        <?= $row['sent_status'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
