<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WellnessHub Dashboard</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary style for the real stress slider — same convention as register.php's banner styles */
    .stress-slider{ width:100%; margin:10px 0; accent-color:#4b73d9; }
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
        <a class="active" href="dashboard.php"><span>▣</span> Dashboard</a>
        <a href="mood-tracking.php"><span>☺</span> Mood Tracking</a>
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
          <?php
            $hour = (int) date('G');
            $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
            $firstName = explode(' ', $_SESSION['full_name'])[0];
          ?>
          <h1><?= $greeting ?>, <?= htmlspecialchars($firstName) ?> 🌱</h1>
          <p class="page-subtitle">Your student dashboard overview.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="dashboard-grid">
        <article class="panel checkin-panel">
          <div class="panel-heading"><div><p class="eyebrow">Daily Check-In</p><h2>How are you feeling today?</h2></div></div>
          <div class="mood-row" aria-label="Mood options">
            <button class="mood active" type="button" data-mood="great"><span>😊</span><small>Great</small></button>
            <button class="mood" type="button" data-mood="good"><span>🙂</span><small>Good</small></button>
            <button class="mood" type="button" data-mood="okay"><span>😐</span><small>Okay</small></button>
            <button class="mood" type="button" data-mood="low"><span>😟</span><small>Low</small></button>
            <button class="mood" type="button" data-mood="struggling"><span>😣</span><small>Struggling</small></button>
          </div>
          <div class="slider-wrap">
            <div class="slider-label"><span>Stress level</span><strong id="stressValue">3</strong></div>
            <input type="range" id="stressSlider" class="stress-slider" min="0" max="10" step="1" value="3" aria-label="Stress level" />
            <div class="scale"><span>0</span><span>10</span></div>
          </div>
          <button class="btn btn-primary checkin-btn" type="button" id="checkinBtn">Submit Check-In</button>
        </article>
        <article class="panel summary-panel">
          <p class="eyebrow">This Week Summary</p>
          <div class="donut" id="moodDonut" role="img" aria-label="Mood summary chart"></div>
          <ul class="legend">
            <li><span class="legend-dot calm"></span> <span id="pct-calm">0</span>% Fine</li>
            <li><span class="legend-dot neutral"></span> <span id="pct-neutral">0</span>% Positive</li>
            <li><span class="legend-dot low"></span> <span id="pct-low">0</span>% Neutral</li>
            <li><span class="legend-dot risk"></span> <span id="pct-risk">0</span>% Low</li>
          </ul>
          <p class="muted" id="summaryEmptyState" style="display:none; font-size:13px; margin-top:8px;">No check-ins yet this week.</p>
        </article>
        <a class="panel action-panel action-journal" href="journal.php"><div class="action-icon">📓</div><h3>Journal</h3><p>Write your thoughts</p></a>
        <a class="panel action-panel action-library" href="self-help-library.php"><div class="action-icon">☯</div><h3>Self-Help</h3><p>Resources & exercises</p></a>
        <a class="panel action-panel action-counselling" href="counselling-booking.php"><div class="action-icon">💬</div><h3>Book Counselling</h3><p>Schedule a session</p></a>
        <a class="panel action-panel action-peer" href="peer-support.php"><div class="action-icon">🤝</div><h3>Peer Support</h3><p>Connect anonymously</p></a>
      </section>
      <section class="feature-strip">
        <div class="strip-item"><span class="strip-icon">🛡</span><div><h4>Secure & Private</h4><p>POPIA Compliant</p></div></div>
        <div class="strip-item"><span class="strip-icon">📊</span><div><h4>Real-time Analytics</h4><p>Early detection & support</p></div></div>
        <div class="strip-item"><span class="strip-icon">🌐</span><div><h4>Accessible Anytime</h4><p>Web-based platform</p></div></div>
        <div class="strip-item"><span class="strip-icon">🤝</span><div><h4>Anonymous Support</h4><p>Safe peer community</p></div></div>
        <div class="strip-item"><span class="strip-icon">💚</span><div><h4>Built for Students</h4><p>Because your mind matters</p></div></div>
      </section>
    </main>
  </div>
  <script src="js/checkin.js"></script>
</body>
</html>
