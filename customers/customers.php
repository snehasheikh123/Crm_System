<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
include("../db/db_connect.php");

$sql = "SELECT customers.*, COUNT(leads.id) AS total_leads 
        FROM customers 
        LEFT JOIN leads ON customers.id = leads.customer_id 
        GROUP BY customers.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customers</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/customers.css">
  <style>
    .action-icons a {
      margin-right: 10px;
      color: #333;
      text-decoration: none;
    }
    .action-icons a:hover {
      color: #007bff;
    }
  </style>
</head>
<body>
<?php include('../sidebar.php'); ?>

  <div class="container">
    <h2><i class="fas fa-users"></i> Customer List with Leads</h2>

    <div class="search-box">
      <input type="text" id="searchInput" placeholder="🔍 Search by name, email, phone or company...">
    </div>

    <table id="customerTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Company</th>
          <th>Total Leads</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['company']) ?></td>
            <td><?= $row['total_leads'] ?></td>
            <td class="action-icons">
              <a href="edit_customer.php?id=<?= $row['id'] ?>" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="delete_customer.php?id=<?= $row['id'] ?>" title="Delete" class="delete-icon" onclick="return confirm('Are you sure you want to delete this customer?');"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <a href="add_customer.php" class="add-btn">➕ Add Customer</a>
    <a href="../dashboard.php" class="back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
  </div>

  <script>
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('customerTable');
    const rows = table.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function () {
      const filter = searchInput.value.toLowerCase();

      for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let matchFound = false;

        for (let j = 0; j < cells.length - 2; j++) { // -2 to exclude "Total Leads" and "Actions"
          const cellText = cells[j].textContent.toLowerCase();
          if (cellText.includes(filter)) {
            matchFound = true;
            break;
          }
        }

        rows[i].style.display = matchFound ? '' : 'none';
      }
    });
  </script>

</body>
</html>
