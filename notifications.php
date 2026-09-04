<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notifications — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary: unread notification indicator, button reset for Mark all as read */
    .notice.unread{ border-left:4px solid #4b73d9; background:#f7f9ff; }
    .panel-heading button{ font:inherit; cursor:pointer; background:none; border:none; color:inherit; text-decoration:underline; }
  </style>
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
          <h1>Notifications</h1>
          <p class="page-subtitle">Stay updated with bookings, mood summaries and support reminders.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="two-col">
        <article class="panel">
          <div class="panel-heading"><h2>Notification Panel</h2><button class="btn btn-ghost btn-small" type="button" id="markReadBtn">Mark all as read</button></div>
          <div class="notification-list" id="notificationList">
            <p class="muted">Loading notifications…</p>
          </div>
        </article>
        <article class="panel">
          <h2>Weekly Mood Summary Email</h2>
          <div class="entry-card">
            <p class="eyebrow">Email Preview</p>
            <h3 id="emailGreeting">Hi <?= htmlspecialchars($firstName) ?>, here is your week in WellnessHub</h3>
            <p class="muted" id="emailBody">Loading…</p>
            <div class="donut" id="emailDonut" style="width:120px;height:120px"></div>
            <a class="btn btn-primary" href="weekly-summary.php">Open Template</a>
          </div>
        </article>
      </section>
    </main>
  </div>
  <script src="js/notifications.js"></script>
</body>
</html>
