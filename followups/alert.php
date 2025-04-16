<?php
include("../db/db_connect.php");

$today = date('Y-m-d');
$stmt = $conn->prepare("
    SELECT f.note, f.followup_date, l.lead_title 
    FROM followups f 
    JOIN leads l ON f.lead_id = l.id 
    WHERE f.followup_date = ?
    ORDER BY f.followup_date DESC
");
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="alert-section">
  <h3>🔔 Today’s Follow-up Alerts (<?= date('d M Y') ?>)</h3>
  <?php if ($result->num_rows > 0): ?>
    <ul class="followup-alerts">
      <?php while ($row = $result->fetch_assoc()): ?>
        <li>
          📌 <strong><?= htmlspecialchars($row['lead_title']) ?></strong><br>
          🗒️ <?= htmlspecialchars($row['note']) ?><br>
          📅 <?= date('d M Y', strtotime($row['followup_date'])) ?>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>✅ No follow-ups scheduled for today.</p>
  <?php endif; ?>
</div>
<style>.alert-section {
  background: #fff3cd;
  border: 1px solid #ffeeba;
  padding: 15px;
  border-radius: 8px;
  margin: 20px 0;
}
.followup-alerts li {
  padding: 10px;
  border-bottom: 1px dashed #ccc;
}
</style>