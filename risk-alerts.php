<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crisis Detection / Risk Alerts — WellnessHub</title>
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
        <a href="counsellor-students.php"><span>👥</span> Students</a>
        <a class="active" href="risk-alerts.php"><span>⚠</span> Risk Alerts</a>
        <a href="counsellor-appointments.php"><span>📅</span> Appointments</a>
        <a href="counsellor-resources.php"><span>▤</span> Resources</a>
        <a href="counsellor-login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main dark-main ">
      <header class="topbar dark-topbar">
        <div>
          <p class="eyebrow">Counsellor Side</p>
          <h1>Crisis Detection / Risk Alerts</h1>
          <p class="page-subtitle">Early warning alerts from mood trends and journal risk markers.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn dark-icon" href="risk-alerts.php" aria-label="Alerts">🔔</a>
          <div class="avatar dark-avatar">N</div>
        </div>
      </header>
      <section class="two-col">
        <article class="panel"><div class="panel-heading"><h2>AI Alerts <span class="pill pill-red">18</span></h2><a href="#">Refresh</a></div><div class="risk-list"><div class="risk-item"><div><h3>Student #T103</h3><p class="muted">Negative mood pattern • increased stress • no engagement</p><span class="risk-score">High Risk</span></div><div class="risk-actions"><button class="btn btn-blue btn-small">Contact Student</button><button class="btn btn-ghost btn-small">View Profile</button></div></div><div class="risk-item"><div><h3>Student #Q045</h3><p class="muted">Negative mood pattern • possible crisis keywords</p><span class="risk-score">High Risk</span></div><div class="risk-actions"><button class="btn btn-blue btn-small">Schedule Session</button><button class="btn btn-ghost btn-small">View Profile</button></div></div><div class="risk-item"><div><h3>Student #3099</h3><p class="muted">Declining mood • irregular check-ins</p><span class="pill pill-orange">Medium Risk</span></div><div class="risk-actions"><button class="btn btn-blue btn-small">Add Note</button><button class="btn btn-ghost btn-small">View Profile</button></div></div></div></article>
        <article class="panel"><h2>Action Note</h2><label class="field"><span>Next Step</span><textarea placeholder="Add internal note for follow-up..."></textarea></label><button class="btn btn-blue btn-full">Save Note</button></article>
      </section>
    </main>
  </div>
</body>
</html>
