<?php
include("../db/db_connect.php");

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$customer_filter = $_GET['customer'] ?? '';
$date_filter = $_GET['date'] ?? '';

$sql = "SELECT leads.*, customers.name AS customer_name 
        FROM leads 
        JOIN customers ON leads.customer_id = customers.id 
        WHERE 1=1";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (leads.lead_title LIKE '%$search%' OR customers.name LIKE '%$search%')";
}

if (!empty($status_filter)) {
    $status_filter = mysqli_real_escape_string($conn, $status_filter);
    $sql .= " AND leads.status = '$status_filter'";
}

if (!empty($customer_filter)) {
    $customer_filter = mysqli_real_escape_string($conn, $customer_filter);
    $sql .= " AND leads.customer_id = '$customer_filter'";
}

if (!empty($date_filter)) {
    $date_filter = mysqli_real_escape_string($conn, $date_filter);
    $sql .= " AND DATE(leads.next_followup) = '$date_filter'";
}

$sql .= " ORDER BY leads.created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($lead = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>".htmlspecialchars($lead['lead_title'])."</td>
                <td>".htmlspecialchars($lead['customer_name'])."</td>
                <td>".htmlspecialchars($lead['status'])."</td>
                <td>".htmlspecialchars($lead['next_followup'])."</td>
                <td>
                    <a href='edit_lead.php?id={$lead['id']}' class='btn-action btn-edit'>✏️ Edit</a>
                    <a href='/crmproject/followups/followups.php?lead_id={$lead['id']}' class='btn-action btn-followup'>📌 Follow-up</a>
                    <a href='/crmproject/followups/email_reminder.php?lead_id={$lead['id']}' class='btn-action btn-email'>📧 Email</a>
                    
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5' style='text-align:center;'>No leads found.</td></tr>";
}
?>
