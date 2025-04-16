<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include("../db/db_connect.php");

if (!isset($_GET['lead_id'])) {
    die("Lead ID not provided.");
}

$lead_id = intval($_GET['lead_id']);

// Add Follow-up
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['note'])) {
        $note = $_POST['note'];
        $stmt = $conn->prepare("INSERT INTO followups (lead_id, note) VALUES (?, ?)");
        $stmt->bind_param("is", $lead_id, $note);
        $stmt->execute();
    }

    // Edit Follow-up
    if (isset($_POST['edit_id']) && isset($_POST['edited_note'])) {
        $edit_id = intval($_POST['edit_id']);
        $edited_note = $_POST['edited_note'];
        $stmt = $conn->prepare("UPDATE followups SET note = ? WHERE id = ?");
        $stmt->bind_param("si", $edited_note, $edit_id);
        $stmt->execute();
    }

    // Delete Follow-up
    if (isset($_POST['delete_id'])) {
        $delete_id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM followups WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
}

// Fetch lead title
$stmt = $conn->prepare("
    SELECT l.lead_title 
    FROM leads l 
    WHERE l.id = ?
");
$stmt->bind_param("i", $lead_id);
$stmt->execute();
$lead_result = $stmt->get_result();
$lead_data = $lead_result->fetch_assoc();
$lead_title = $lead_data['lead_title'] ?? '';

// Fetch all followups
$followup_query = "SELECT * FROM followups WHERE lead_id = $lead_id ORDER BY followup_date DESC";
$followup_result = mysqli_query($conn, $followup_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Follow-ups</title>
  <link rel="stylesheet" href="../assets/css/followups.css">
</head>
<body>
  <div class="card">
    <h2>📋 Add Follow-up for Lead: <strong><?= htmlspecialchars($lead_title) ?></strong></h2>
    <form method="post">
      <label>🗒️ Note</label>
      <textarea name="note" rows="4" required placeholder="Write follow-up note here..."></textarea>
      <button type="submit">➕ Add Follow-up</button>
      <a href="../leads/leads.php" class="back">🔙 Back to Leads</a>
    </form>

    <h3>📜 Previous Follow-ups</h3>
    <ul class="followup-list">
      <?php while ($row = mysqli_fetch_assoc($followup_result)) { ?>
        <li data-id="<?= $row['id'] ?>">
          <span class="date">🕒 <?= date('d M Y, H:i', strtotime($row['followup_date'])) ?>:</span>
          <span class="note-text"><?= htmlspecialchars($row['note']) ?></span>

          <!-- Edit form -->
          <form class="edit-form" style="display: none;" method="post">
            <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
            <textarea name="edited_note" rows="2"><?= htmlspecialchars($row['note']) ?></textarea>
            <button type="submit" name="save_edit">💾 Save</button>
            <button type="button" class="cancel-edit">❌ Cancel</button>
          </form>

          <!-- Action buttons -->
          <div class="actions">
            <button type="submit" class="edit-btn">✏️ Edit</button>
            <form method="post" class="delete-form">
              <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
              <button type="submit" class="delete-btn">🗑️ Delete</button>
            </form>
          </div>
        </li>
      <?php } ?>
    </ul>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <p>❗ Are you sure you want to delete this follow-up?</p>
      <div class="modal-buttons">
        <button id="confirmDelete" class="yes-btn">✅ Yes</button>
        <button id="cancelDelete" class="no-btn">❌ No</button>
      </div>
    </div>
  </div>

  <script>
    // Edit buttons
    document.querySelectorAll(".edit-btn").forEach(button => {
      button.addEventListener("click", function () {
        const li = this.closest("li");
        li.querySelector(".note-text").style.display = "none";
        li.querySelector(".edit-form").style.display = "block";
        this.style.display = "none";
      });
    });

    // Cancel edit buttons
    document.querySelectorAll(".cancel-edit").forEach(button => {
      button.addEventListener("click", function () {
        const li = this.closest("li");
        li.querySelector(".note-text").style.display = "inline";
        li.querySelector(".edit-form").style.display = "none";
        li.querySelector(".edit-btn").style.display = "inline-block";
      });
    });

    // Delete modal logic
    let currentDeleteForm = null;
    const modal = document.getElementById("deleteModal");
    const confirmDelete = document.getElementById("confirmDelete");
    const cancelDelete = document.getElementById("cancelDelete");

    document.querySelectorAll(".delete-form").forEach(form => {
      form.addEventListener("submit", function (e) {
        e.preventDefault();
        currentDeleteForm = this;
        modal.style.display = "flex";
      });
    });

    confirmDelete.addEventListener("click", function () {
      if (currentDeleteForm) currentDeleteForm.submit();
    });

    cancelDelete.addEventListener("click", function () {
      modal.style.display = "none";
      currentDeleteForm = null;
    });

    window.addEventListener("click", function (e) {
      if (e.target === modal) {
        modal.style.display = "none";
        currentDeleteForm = null;
      }
    });
  </script>
</body>
</html>
