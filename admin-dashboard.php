<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard — WellnessHub</title>
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
        <a class="active" href="admin-dashboard.php"><span>▣</span> Dashboard</a>
        <a href="admin-users.php"><span>👥</span> Users</a>
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
          <h1>Admin Dashboard</h1>
          <p class="page-subtitle">Overview of the WellnessHub platform.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="page-grid"><div class="kpi-grid"><div class="kpi-card"><span>Total Users</span><strong>5,000</strong></div><div class="kpi-card"><span>Active Students</span><strong>4,200</strong></div><div class="kpi-card"><span>Counsellors</span><strong>25</strong></div><div class="kpi-card"><span>Sessions This Month</span><strong>320</strong></div></div><section class="two-col"><article class="panel"><h2>User Growth</h2><svg class="svg-chart" viewBox="0 0 420 180"><path class="chart-gridline" d="M20 35H400M20 80H400M20 125H400"/><path class="chart-line" d="M30 130 L90 118 L150 70 L210 106 L270 42 L330 88 L390 36" style="stroke:#e87ac8"/><circle class="chart-dot" cx="30" cy="130" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="90" cy="118" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="150" cy="70" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="210" cy="106" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="270" cy="42" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="330" cy="88" r="5" style="stroke:#e87ac8"/><circle class="chart-dot" cx="390" cy="36" r="5" style="stroke:#e87ac8"/></svg></article><article class="panel"><h2>Top Concerns</h2><div class="donut purple-donut"></div><ul class="legend"><li><span class="legend-dot neutral"></span> Stress 40%</li><li><span class="legend-dot risk"></span> Anxiety 30%</li><li><span class="legend-dot low"></span> Academic Pressure 20%</li><li><span class="legend-dot calm"></span> Other 10%</li></ul></article></section></section>
    </main>
  </div>
</body>
</html>
