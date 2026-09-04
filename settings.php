<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <script src="js/theme.js"></script>
  <style>
    /* Supplementary: real switches need a click handler and a disabled
       (non-real-toggle) state for settings that don't have two real states */
    .switch{ cursor:pointer; }
    .switch.disabled{ cursor:not-allowed; opacity:0.6; }
  </style>
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
        <a href="notifications.php"><span>🔔</span> Notifications</a>
        <a class="active" href="settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main ">
      <header class="topbar">
        <div>
          <p class="eyebrow">Student Side</p>
          <h1>Settings</h1>
          <p class="page-subtitle">Manage privacy, notifications and display preferences.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="two-col">
        <article class="panel">
          <h2>Privacy & Security</h2>
          <div class="setting-row">
            <div><strong>POPIA privacy protection</strong><p class="muted">Keep journal entries and mood logs private.</p></div>
            <span class="switch on disabled" title="Always on — your journal and mood data are only ever visible to you, this isn't a togglable setting."></span>
          </div>
          <div class="setting-row">
            <div><strong>Anonymous peer support</strong><p class="muted">Hide your identity when posting.</p></div>
            <span class="switch on disabled" title="Always on — peer support posts are always anonymous by design, there's no non-anonymous mode."></span>
          </div>
          <div class="setting-row">
            <div><strong>Biometric login</strong><p class="muted">Enable biometric sign-in on supported devices.</p></div>
            <span class="switch disabled" title="Not available in this demo yet."></span>
          </div>
        </article>
        <article class="panel">
          <h2>Display & Notifications</h2>
          <div class="setting-row">
            <div><strong>Dark mode</strong><p class="muted">Use dark mode for night-time journaling.</p></div>
            <span class="switch" id="switchDarkMode" data-key="dark_mode"></span>
          </div>
          <div class="setting-row">
            <div><strong>Booking reminders</strong><p class="muted">Receive counselling appointment reminders.</p></div>
            <span class="switch" id="switchBookingReminders" data-key="booking_reminders"></span>
          </div>
          <div class="setting-row">
            <div><strong>Weekly mood email</strong><p class="muted">Receive Monday mood summaries.</p></div>
            <span class="switch" id="switchWeeklyEmail" data-key="weekly_mood_email" title="Saved for when email sending is built — no actual email is sent yet."></span>
          </div>
        </article>
      </section>
    </main>
  </div>
  <script src="js/settings.js"></script>
</body>
</html>
