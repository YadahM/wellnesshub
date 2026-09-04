<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Weekly Mood Summary — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="app-body">
  <div class="app-shell">
    <aside class="sidebar student-sidebar">
      <a class="brand-mini sidebar-brand" href="index.php">
        <span class="mini-leaf" aria-hidden="true">☘</span>
        <span>WellnessHub</span>
      </a>

      <nav class="side-nav" aria-label="Student navigation">
        <a href="dashboard.php"><span>▣</span> Dashboard</a>
        <a href="mood-tracking.php"><span>☺</span> Mood Tracking</a>
        <a href="journal.php"><span>✎</span> Journal</a>
        <a href="self-help-library.php"><span>▤</span> Self-Help Library</a>
        <a href="counselling-booking.php"><span>♡</span> Book Counselling</a>
        <a href="peer-support.php"><span>☷</span> Peer Support</a>
        <a href="achievements.php"><span>★</span> Achievements</a>
        <a class="active" href="notifications.php"><span>🔔</span> Notifications</a>
        <a href="settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main ">
      <header class="topbar">
        <div>
          <p class="eyebrow">Student Side</p>
          <h1>Weekly Mood Summary</h1>
          <p class="page-subtitle">Email template layout for the weekly wellness report.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <div class="avatar">S</div>
        </div>
      </header>
      <section class="page-grid">
        <article class="panel" style="max-width:780px;margin:auto;width:100%"><div class="brand-mini"><span class="mini-leaf">☘</span><span>WellnessHub Weekly Summary</span></div><h2>Hello Sarah, your weekly wellbeing report is ready.</h2><p class="muted">Here is a private snapshot of your check-ins, mood trend and recommended next steps.</p><div class="three-col"><div class="stat-mini"><strong>5</strong><span>Check-ins</span></div><div class="stat-mini"><strong>12%</strong><span>Mood Up</span></div><div class="stat-mini"><strong>2</strong><span>Resources Used</span></div></div><div class="insight" style="margin-top:18px"><strong>Suggested action:</strong> Continue using the guided breathing exercise and consider booking a counsellor session if stress increases.</div><a class="btn btn-primary" href="notifications.php" style="margin-top:18px">Back to Notifications</a></article>
      </section>
    </main>
  </div>
</body>
</html>
