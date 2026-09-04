<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Settings — WellnessHub</title>
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
        <a href="system-logs.php"><span>☷</span> System Logs</a>
        <a class="active" href="admin-settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main admin-main ">
      <header class="topbar admin-topbar">
        <div>
          <p class="eyebrow">Admin Side</p>
          <h1>Admin Settings</h1>
          <p class="page-subtitle">Configure platform privacy and security options.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="two-col"><article class="panel"><h2>Security Settings</h2><div class="setting-row"><div><strong>Role-based access control</strong><p class="muted">Student, counsellor and admin permissions.</p></div><span class="switch on"></span></div><div class="setting-row"><div><strong>Database backup</strong><p class="muted">Daily backup before deployment.</p></div><span class="switch on"></span></div><div class="setting-row"><div><strong>Audit logging</strong><p class="muted">Track system changes and report exports.</p></div><span class="switch on"></span></div></article><article class="panel"><h2>Platform Settings</h2><div class="setting-row"><div><strong>Weekly mood reports</strong><p class="muted">Enable automated weekly email summaries.</p></div><span class="switch on"></span></div><div class="setting-row"><div><strong>Risk alert delivery</strong><p class="muted">Send high-risk alerts to counsellors.</p></div><span class="switch on"></span></div><div class="setting-row"><div><strong>Anonymous board moderation</strong><p class="muted">Require moderation rules for posts.</p></div><span class="switch on"></span></div></article></section>
    </main>
  </div>
</body>
</html>
