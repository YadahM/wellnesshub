<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Counsellor Appointment Calendar — WellnessHub</title>
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
        <a href="risk-alerts.php"><span>⚠</span> Risk Alerts</a>
        <a class="active" href="counsellor-appointments.php"><span>📅</span> Appointments</a>
        <a href="counsellor-resources.php"><span>▤</span> Resources</a>
        <a href="counsellor-login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main dark-main ">
      <header class="topbar dark-topbar">
        <div>
          <p class="eyebrow">Counsellor Side</p>
          <h1>Counsellor Appointment Calendar</h1>
          <p class="page-subtitle">Manage daily appointments and availability.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn dark-icon" href="risk-alerts.php" aria-label="Alerts">🔔</a>
          <div class="avatar dark-avatar">N</div>
        </div>
      </header>
      <section class="page-grid"><article class="panel"><div class="panel-heading"><h2>Today — June 2026</h2><div class="inline"><button class="btn btn-blue btn-small">Calendar</button><button class="btn btn-ghost btn-small">List View</button></div></div><div class="timeline-grid"><div class="time-cell time-head">Time</div><div class="time-cell time-head">Mon 2</div><div class="time-cell time-head">Tue 3</div><div class="time-cell time-head">Wed 4</div><div class="time-cell time-head">Thu 5</div><div class="time-cell time-head">Fri 6</div><div class="time-cell">09:00</div><div class="time-cell"><div class="appointment-block">Student #213<br>09:00</div></div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block">Student #045<br>09:30</div></div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block green-block">Student #120<br>09:00</div></div><div class="time-cell">10:00</div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block green-block">Student #116<br>10:30</div></div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block">Student #390<br>10:00</div></div><div class="time-cell"></div><div class="time-cell">11:00</div><div class="time-cell"></div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block">Student #305<br>11:30</div></div><div class="time-cell"></div><div class="time-cell"><div class="appointment-block green-block">Available<br>11:30</div></div></div></article></section>
    </main>
  </div>
</body>
</html>
