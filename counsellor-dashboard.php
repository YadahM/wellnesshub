<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome, Dr. Naidoo — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="app-body dark-body counsellor-body">
  <div class="app-shell dark-shell">
    <aside class="sidebar counsellor-sidebar">
      <a class="brand-mini sidebar-brand" href="index.php">
        <span class="mini-leaf" aria-hidden="true">☘</span>
        <span>WellnessHub</span>
      </a>

      <nav class="side-nav" aria-label="Counsellor navigation">
        <a class="active" href="counsellor-dashboard.php"><span>▣</span> Dashboard</a>
        <a href="counsellor-students.php"><span>👥</span> Students</a>
        <a href="risk-alerts.php"><span>⚠</span> Risk Alerts</a>
        <a href="counsellor-appointments.php"><span>📅</span> Appointments</a>
        <a href="counsellor-resources.php"><span>▤</span> Resources</a>
        <a href="counsellor-login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main dark-main ">
      <header class="topbar dark-topbar">
        <div>
          <p class="eyebrow">Counsellor Side</p>
          <h1>Welcome, Dr. Naidoo</h1>
          <p class="page-subtitle">Here is today's counselling overview.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn dark-icon" href="risk-alerts.php" aria-label="Alerts">🔔</a>
          <div class="avatar dark-avatar">N</div>
        </div>
      </header>
      <section class="page-grid">
        <div class="kpi-grid"><div class="kpi-card"><span>Students Checked In</span><strong>350</strong><small class="green">+12% this week</small></div><div class="kpi-card"><span>High Risk Students</span><strong class="red">18</strong><small class="red">Needs attention</small></div><div class="kpi-card"><span>Appointments Today</span><strong>42</strong><small class="green">7 scheduled</small></div><div class="kpi-card"><span>Resolved Alerts</span><strong>26</strong><small class="green">Improving</small></div></div>
        <section class="two-col"><article class="panel"><h2>Mood Trend This Week</h2><svg class="svg-chart" viewBox="0 0 420 180"><path class="chart-gridline" d="M20 35H400M20 80H400M20 125H400"/><path class="chart-line" d="M30 130 L90 92 L150 42 L210 84 L270 65 L330 25 L390 52"/><circle class="chart-dot" cx="30" cy="130" r="5"/><circle class="chart-dot" cx="90" cy="92" r="5"/><circle class="chart-dot" cx="150" cy="42" r="5"/><circle class="chart-dot" cx="210" cy="84" r="5"/><circle class="chart-dot" cx="270" cy="65" r="5"/><circle class="chart-dot" cx="330" cy="25" r="5"/><circle class="chart-dot" cx="390" cy="52" r="5"/></svg></article><article class="panel"><h2>Mood Distribution</h2><div class="donut"></div><ul class="legend"><li><span class="legend-dot neutral"></span> 50% Positive</li><li><span class="legend-dot calm"></span> 30% Neutral</li><li><span class="legend-dot risk"></span> 20% Low</li></ul></article></section>
      </section>
    </main>
  </div>
</body>
</html>
