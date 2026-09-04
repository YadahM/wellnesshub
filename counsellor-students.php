<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Overview — WellnessHub</title>
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
        <a href="counsellor-dashboard.php"><span>▣</span> Dashboard</a>
        <a class="active" href="counsellor-students.php"><span>👥</span> Students</a>
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
          <h1>Student Overview</h1>
          <p class="page-subtitle">View student wellbeing summaries and follow-up status.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn dark-icon" href="risk-alerts.php" aria-label="Alerts">🔔</a>
          <div class="avatar dark-avatar">N</div>
        </div>
      </header>
      <section class="panel"><div class="panel-heading"><h2>Students</h2><input class="input-like" style="max-width:260px" placeholder="Search student..." /></div><table class="data-table"><thead><tr><th>Student</th><th>Mood</th><th>Risk</th><th>Last Check-in</th><th>Action</th></tr></thead><tbody><tr><td>Student #T103</td><td>Low</td><td><span class="pill pill-red">High</span></td><td>Today</td><td><a class="btn btn-blue btn-small" href="risk-alerts.php">View</a></td></tr><tr><td>Student #Q045</td><td>Struggling</td><td><span class="pill pill-red">High</span></td><td>Yesterday</td><td><a class="btn btn-blue btn-small" href="risk-alerts.php">View</a></td></tr><tr><td>Student #120</td><td>Good</td><td><span class="pill pill-green">Low</span></td><td>Today</td><td><a class="btn btn-ghost btn-small" href="#">Profile</a></td></tr></tbody></table></section>
    </main>
  </div>
</body>
</html>
