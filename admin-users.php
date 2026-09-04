<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Management — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="app-body admin-body">
  <div class="app-shell admin-shell">
    <aside class="sidebar admin-sidebar">
      <a class="brand-mini sidebar-brand" href="index.php">
        <span class="mini-leaf" aria-hidden="true">☘</span>
        <span>WellnessHub</span>
      </a>

      <nav class="side-nav" aria-label="Admin navigation">
        <a href="admin-dashboard.php"><span>▣</span> Dashboard</a>
        <a class="active" href="admin-users.php"><span>👥</span> Users</a>
        <a href="admin-counsellors.php"><span>♡</span> Counsellors</a>
        <a href="admin-reports.php"><span>📊</span> Reports</a>
        <a href="system-logs.php"><span>☷</span> System Logs</a>
        <a href="admin-settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main admin-main ">
      <header class="topbar admin-topbar">
        <div>
          <p class="eyebrow">Admin Side</p>
          <h1>User Management</h1>
          <p class="page-subtitle">Manage student, counsellor and admin accounts.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="panel"><div class="panel-heading"><h2>Users</h2><button class="btn btn-purple btn-small">Add User</button></div><table class="data-table"><thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Last Login</th><th>Action</th></tr></thead><tbody><tr><td>Sarah Naidoo</td><td>Student</td><td><span class="pill pill-green">Active</span></td><td>Today</td><td><button class="btn btn-ghost btn-small">Edit</button></td></tr><tr><td>Dr. Naidoo</td><td>Counsellor</td><td><span class="pill pill-green">Active</span></td><td>Today</td><td><button class="btn btn-ghost btn-small">Edit</button></td></tr><tr><td>Admin User</td><td>Admin</td><td><span class="pill pill-blue">Secure</span></td><td>Yesterday</td><td><button class="btn btn-ghost btn-small">Edit</button></td></tr></tbody></table></section>
    </main>
  </div>
</body>
</html>
