<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
include("../db/db_connect.php");

// Fetch customers for dropdown
$customers = mysqli_query($conn, "SELECT id, name FROM customers ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Leads</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/manage_lead.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include("../sidebar.php"); ?>

<div class="container">
  <div class="main-content">
    <div class="leads-box">
      <h2><i class="fas fa-address-book"></i> All Leads</h2>

      <!-- Filter Form -->
      <form id="filter-form" class="filter-form">
        <input type="text" name="search" placeholder="Search...">

        <select name="status">
          <option value="">All Status</option>
          <option value="New">New</option>
          <option value="In Progress">In Progress</option>
          <option value="Won">Won</option>
          <option value="Lost">Lost</option>
        </select>

        <select name="customer">
          <option value="">All Customers</option>
          <?php while ($cust = mysqli_fetch_assoc($customers)) { ?>
            <option value="<?= $cust['id'] ?>"><?= htmlspecialchars($cust['name']) ?></option>
          <?php } ?>
        </select>

        <input type="date" name="date">
        <button type="submit" class="btn-action">🔍 Filter</button>
        <button type="button" id="reset-btn" class="btn-action">🔄 Reset</button>
      </form>

      <!-- Leads Table -->
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
        <tbody id="leads-table-body">
          <!-- AJAX-loaded rows will be here -->
        </tbody>
      </table>

      <a href="add_lead.php" class="add-btn">➕ Add New Lead</a>
    </div>
  </div>
</div>

<script>
function loadLeads() {
  const formData = $("#filter-form").serialize();
  $.ajax({
    url: "filter_leads.php",
    method: "GET",
    data: formData,
    success: function(response) {
      $("#leads-table-body").html(response);
    }
  });
}

$(document).ready(function() {
  loadLeads();

  $("#filter-form").on("submit", function(e) {
    e.preventDefault();
    loadLeads();
  });

  $("[name='search']").on("keyup", function() {
    loadLeads();
  });

  $("[name='status'], [name='customer'], [name='date']").on("change", function() {
    loadLeads();
  });

  $("#reset-btn").on("click", function() {
    $("#filter-form")[0].reset();
    loadLeads();
  });
});
</script>

</body>
</html>
