<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>System Logs — WellnessHub</title>
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
        <a href="admin-users.php"><span>👥</span> Users</a>
        <a href="admin-counsellors.php"><span>♡</span> Counsellors</a>
        <a href="admin-reports.php"><span>📊</span> Reports</a>
        <a class="active" href="system-logs.php"><span>☷</span> System Logs</a>
        <a href="admin-settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main admin-main ">
      <header class="topbar admin-topbar">
        <div>
          <p class="eyebrow">Admin Side</p>
          <h1>System Logs</h1>
          <p class="page-subtitle">Monitor security, login and platform activity.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="panel"><div class="panel-heading"><h2>Recent Activity</h2><button class="btn btn-purple btn-small">Export Logs</button></div><div class="notification-list"><div class="notice"><span class="notice-icon">🔐</span><div><strong>Admin login successful</strong><p class="muted">IP verified · Role-based access passed</p></div><span>5 min ago</span></div><div class="notice"><span class="notice-icon">📊</span><div><strong>Monthly PDF report generated</strong><p class="muted">Report exported by Admin</p></div><span>1h ago</span></div><div class="notice"><span class="notice-icon">🛡</span><div><strong>Security test completed</strong><p class="muted">No SQL injection or XSS issue detected</p></div><span>Today</span></div></div></section>
    </main>
  </div>
</body>
</html>
