<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Achievements — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="js/theme.js"></script>
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
        <a class="active" href="achievements.php"><span>★</span> Achievements</a>
        <a href="notifications.php"><span>🔔</span> Notifications</a>
        <a href="settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main ">
      <header class="topbar">
        <div>
          <p class="eyebrow">Student Side</p>
          <h1>Achievements</h1>
          <p class="page-subtitle">Keep going! You are doing amazing.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="page-grid">
        <article class="panel level-card"><div class="level-badge">☘</div><div><p class="eyebrow">Your Wellness Journey</p><h2 id="levelLabel">Level –</h2><div class="progress-bar" aria-label="Level progress"><span id="xpBar" style="width:0%"></span></div><p class="muted" id="xpLabel">– / – XP</p></div><a class="btn btn-ghost" href="mood-tracking.php">Earn XP</a></article>
        <article class="panel"><div class="panel-heading"><h2>Badges</h2></div><div class="badge-grid" id="badgeGrid"><p class="muted">Loading badges…</p></div></article>
        <article class="panel"><h2>Your Stats</h2><div class="stat-grid" id="statGrid"><p class="muted">Loading stats…</p></div></article>
      </section>
    </main>
  </div>
  <script src="js/achievements.js"></script>
</body>
</html>
