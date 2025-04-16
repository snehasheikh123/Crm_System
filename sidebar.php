<?php
//session_start(); // ✅ Required if sidebar.php is accessed directly
 $role = $_SESSION['role'] ?? 'guest';
$username = $_SESSION['username'] ?? 'User';
?>
<div class="sidebar">
    <h2>Welcome, <?= htmlspecialchars($username) ?> 👋</h2>
    <p>Logged in as <strong><?= ucfirst($role) ?></strong></p>
    <ul>
        <?php if ($role == 'admin'): ?>
            <li><a href="../dashboard/admin_dashboard.php">📊 View Analytics Dashboard</a></li>
            <li><a href="../customers/customers.php"> 👥 Manage Customer</a></li>
            <li><a href="../leads/manage_lead.php">📋 Manage Leads</a></li>
            <li><a href="../admin/reminder.php">⏰ Manage Reminders</a></li>
            <li><a href="roles.php">🔐 Manage Roles</a></li>

        <?php elseif ($role == 'sales'): ?>
            <li><a href="assigned_leads.php">📋 View Assigned Leads</a></li>
            <li><a href="add_followup.php">➕ Add Follow-Ups</a></li>
            <li><a href="email_reminders.php">📧 Send Email Reminders</a></li>
            <li><a href="update_lead_status.php">📝 Update Lead Status</a></li>

        <?php elseif ($role == 'support'): ?>
            <li><a href="customer_complaints.php">📨 View Complaints/Inquiries</a></li>
            <li><a href="respond_chats.php">💬 Respond via Email/Chat</a></li>
            <li><a href="track_resolution.php">📍 Track Resolution Status</a></li>

        <?php elseif ($role == 'marketing'): ?>
            <li><a href="campaigns.php">📢 Access Campaigns</a></li>
            <li><a href="marketing_content.php">📝 Add/Edit Marketing Content</a></li>
            <li><a href="conversion_reports.php">📈 View Lead Conversion Reports</a></li>

        <?php elseif ($role == 'data_entry'): ?>
            <li><a href="add_leads.php">➕ Add New Leads/Customers</a></li>
            <li><a href="edit_basic_info.php">📝 Edit Basic Information</a></li>
            <li><a href="upload_docs.php">📂 Upload Customer Documents</a></li>

        <?php elseif ($role == 'customer'): ?>
            <li><a href="my_profile.php">🙍 View My Profile</a></li>
            <li><a href="support_status.php">📍 Track Support/Tickets</a></li>
            <li><a href="request_support.php">📩 Request Support/Updates</a></li>
        <?php endif; ?>

        <li><a href="../auth/logout.php" class="logout">🔒 Logout</a></li>
    </ul>
</div>

<style>
body {
    display: flex;
    font-family: Arial;
}
.sidebar {
    width: 260px;
    background: #f4f4f4;
    padding: 20px;
    height: 100vh;
}
.sidebar h2 {
    font-size: 20px;
    margin-bottom: 10px;
}
.sidebar ul {
    list-style: none;
    padding: 0;
}
.sidebar ul li {
    margin: 10px 0;
}
.sidebar ul li a {
    text-decoration: none;
    padding: 10px;
    display: block;
    background: #007bff;
    color: white;
    border-radius: 5px;
}
.sidebar ul li a.logout {
    background: #dc3545;
}
</style>
