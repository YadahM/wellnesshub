<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mood Tracking — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles for tab switching and the new Stats view */
    .tab-buttons .btn.active{ background:#4b73d9; color:#fff; }
    .stats-grid{ display:grid; grid-template-columns:repeat(auto-fit, minmax(140px,1fr)); gap:16px; padding:12px 0; }
    .stat-card{ background:#f4f6fb; border-radius:12px; padding:18px; text-align:center; }
    .stat-value{ display:block; font-size:28px; font-weight:800; color:#2f3b52; }
    .stat-label{ display:block; font-size:13px; color:#6b7280; margin-top:4px; }
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
        <a class="active" href="mood-tracking.php"><span>☺</span> Mood Tracking</a>
        <a href="journal.php"><span>✎</span> Journal</a>
        <a href="self-help-library.php"><span>▤</span> Self-Help Library</a>
        <a href="counselling-booking.php"><span>♡</span> Book Counselling</a>
        <a href="peer-support.php"><span>☷</span> Peer Support</a>
        <a href="achievements.php"><span>★</span> Achievements</a>
        <a href="notifications.php"><span>🔔</span> Notifications</a>
        <a href="settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main ">
      <header class="topbar">
        <div>
          <p class="eyebrow">Student Side</p>
          <h1>Mood Tracking</h1>
          <p class="page-subtitle">Review calendar check-ins, mood trends, and stress patterns.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <div class="inline tab-buttons" style="margin-bottom:14px;">
        <button class="btn btn-ghost btn-small active" type="button" data-tab="calendar">Calendar</button>
        <button class="btn btn-ghost btn-small" type="button" data-tab="charts">Charts</button>
        <button class="btn btn-ghost btn-small" type="button" data-tab="stats">Stats</button>
      </div>
      <section class="two-col">
        <article class="panel" id="leftPanel"></article>
        <article class="panel" id="rightPanel"></article>
      </section>

      <!-- View blocks: JS moves these between #leftPanel and #rightPanel. Not shown until attached. -->
      <template id="blockCalendar">
        <div class="calendar-header"><h2 id="calendarMonthLabel">Loading…</h2></div>
        <div class="calendar-grid" id="calendarGrid">
          <div class="day-name">Sun</div><div class="day-name">Mon</div><div class="day-name">Tue</div><div class="day-name">Wed</div><div class="day-name">Thu</div><div class="day-name">Fri</div><div class="day-name">Sat</div>
        </div>
      </template>

      <template id="blockCharts">
        <div class="stack">
          <div class="chart-card"><h2>Mood Trend This Week</h2><svg class="svg-chart" id="moodTrendSvg" viewBox="0 0 420 180" role="img" aria-label="Mood trend line chart"><defs><linearGradient id="moodArea" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#4b73d9"/><stop offset="1" stop-color="#4b73d9" stop-opacity="0"/></linearGradient></defs><path class="chart-gridline" d="M20 35H400M20 80H400M20 125H400"/><g id="chartLines"></g><g id="chartDots"></g><g id="chartLabels"></g></svg></div>
          <div class="chart-card"><h2>Stress Level</h2><div class="bar-chart" id="stressBarChart"></div></div>
          <div class="insight"><strong>Weekly Insight:</strong> <span id="insightText">Loading…</span></div>
        </div>
      </template>

      <template id="blockStats">
        <h2>This Week's Stats</h2>
        <div class="stats-grid" id="statsGrid">
          <div class="stat-card"><span class="stat-value" id="statCheckins">–</span><span class="stat-label">Check-ins this week</span></div>
          <div class="stat-card"><span class="stat-value" id="statAvgMood">–</span><span class="stat-label">Average mood (1–5)</span></div>
          <div class="stat-card"><span class="stat-value" id="statAvgStress">–</span><span class="stat-label">Average stress (0–10)</span></div>
        </div>
      </template>
    </main>
  </div>
  <script src="js/mood-tracking.js"></script>
</body>
</html>
