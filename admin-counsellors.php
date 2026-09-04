<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Counsellors — WellnessHub</title>
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
        <a class="active" href="admin-counsellors.php"><span>♡</span> Counsellors</a>
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
          <h1>Counsellors</h1>
          <p class="page-subtitle">Manage counsellor workload and availability.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="three-col"><article class="panel"><div class="face">👩🏽</div><h2>Dr. Naidoo</h2><p class="muted">25 appointments this month</p><span class="pill pill-green">Available</span></article><article class="panel"><div class="face blue-face">👨🏽</div><h2>Mr. Govender</h2><p class="muted">19 appointments this month</p><span class="pill pill-orange">Busy</span></article><article class="panel"><div class="face green-face">👩🏾</div><h2>Ms. Dlamini</h2><p class="muted">21 appointments this month</p><span class="pill pill-green">Available</span></article></section>
    </main>
  </div>
</body>
</html>
