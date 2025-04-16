<?php
// leads.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

$sql = "SELECT leads.*, customers.name AS customer_name FROM leads 
        JOIN customers ON leads.customer_id = customers.id 
        ORDER BY leads.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Leads</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .leads-box {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .leads-box h2 {
      font-size: 24px;
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .leads-box h2 i {
      color: #6a1b9a;
      font-size: 24px;
    }

    .leads-box .btn {
      display: inline-block;
      padding: 10px 15px;
      background: #28a745;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .leads-box .btn:hover {
      background: #218838;
    }

    .leads-box table {
      width: 100%;
      border-collapse: collapse;
    }

    .leads-box th, .leads-box td {
      padding: 12px 16px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .leads-box th {
      background-color: #6a1b9a;
      color: white;
    }

    .leads-box tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .leads-box tbody tr:hover {
      background-color: #f1f1f1;
    }

    .btn-edit, .btn-followup, .btn-email {
      padding: 5px 10px;
      color: white;
      border-radius: 6px;
      margin-right: 5px;
      text-decoration: none;
    }

    .btn-edit { background: #007bff; }
    .btn-edit:hover { background: #0056b3; }

    .btn-followup { background: #17a2b8; }
    .btn-followup:hover { background: #117a8b; }

    .btn-email { background: #ffc107; color: #212529; }
    .btn-email:hover { background: #e0a800; color: #212529; }

    .back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #6a1b9a;
    }

    .back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="leads-box">
    <h2><i class="fas fa-address-book"></i> All Leads</h2>
    <a href="add_lead.php" class="btn">➕ Add New Lead</a>
    <table>
      <thead>
        <tr>
          <th>Lead Title</th>
          <th>Customer</th>
          <th>Status</th>
          <th>Next Follow-up</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($lead = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= htmlspecialchars($lead['lead_title']) ?></td>
            <td><?= htmlspecialchars($lead['customer_name']) ?></td>
            <td><?= htmlspecialchars($lead['status']) ?></td>
            <td><?= htmlspecialchars($lead['next_followup']) ?></td>
            <td>
              <a href="edit_lead.php?id=<?= $lead['id'] ?>" class="btn-edit">✏️ Edit</a>
              <a href="/crmproject/followups/followups.php?lead_id=<?= $lead['id'] ?>" class="btn-followup">📌 Follow-ups</a>
<a href="/crmproject/followups/email_reminder.php?lead_id=<?= $lead['id'] ?>" class="btn-email">📧 Email</a>



            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <a href="../dashboard.php" class="back">← Back to Dashboard</a>
  </div>
</body>
</html>
