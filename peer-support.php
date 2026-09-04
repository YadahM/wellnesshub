<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Peer Support Board — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles for tab switching and interactive post actions */
    .tabs a.tab-btn{ cursor:pointer; background:none; border:none; border-bottom:3px solid transparent; font:inherit; padding:10px 14px; color:var(--muted); font-size:13px; }
    .tabs a.tab-btn.active{ color:var(--green-700); border-color:var(--green-700); }
    .post-actions span{ cursor:pointer; user-select:none; }
    .post-actions span.liked{ color:#c44848; font-weight:900; }
    .reply-thread{ margin-top:10px; padding-top:10px; border-top:1px solid var(--line); display:none; }
    .reply-item{ font-size:13px; color:var(--muted); margin-bottom:8px; }
    .reply-input{ display:flex; gap:8px; margin-top:8px; }
    .reply-input input{ flex:1; padding:8px 10px; border:1px solid var(--line); border-radius:8px; font:inherit; }
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
        <a class="active" href="peer-support.php"><span>☷</span> Peer Support</a>
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
          <h1>Peer Support Board</h1>
          <p class="page-subtitle">Share your thoughts anonymously. You are not alone.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="two-col">
        <div class="stack">
          <article class="panel post-box">
            <div class="panel-heading"><h2>Anonymous Peer Support Board</h2><span class="pill pill-green">Moderated</span></div>
            <label class="field"><span>What is on your mind?</span><textarea id="postContent" placeholder="Share anonymously with the community..."></textarea></label>
            <div class="inline" style="margin-top:8px;">
              <select id="postCategory" class="btn btn-ghost btn-small">
                <option value="Support">Support</option>
                <option value="Vent">Vent</option>
                <option value="Advice">Advice</option>
                <option value="Encouragement">Encouragement</option>
              </select>
              <button class="btn btn-primary" type="button" id="postBtn">Post Anonymously</button>
            </div>
          </article>
          <article class="panel">
            <div class="tabs" id="feedTabs">
              <a class="tab-btn active" data-tab="recent">Recent</a>
              <a class="tab-btn" data-tab="popular">Popular</a>
              <a class="tab-btn" data-tab="mine">My Posts</a>
            </div>
            <div class="stack" id="postFeed">
              <p class="muted">Loading posts…</p>
            </div>
          </article>
        </div>
        <aside class="extra-feature-stack">
          <div class="feature-card"><div class="spread"><strong>Extra Features</strong><span class="toggle"></span></div><p>Dark Mode ready for low-light use.</p><a class="btn btn-small" href="settings.php">View Settings</a></div>
          <div class="feature-card purple-feature"><strong>Weekly Mood Summary</strong><p>Email delivered every Monday.</p><a class="btn btn-small" href="weekly-summary.php">View Example</a></div>
          <div class="feature-card red-feature"><strong>Need Help Now?</strong><p>You are not alone. Contact SADAG 24/7 Helpline.</p><a class="btn btn-small" href="tel:0800567567">Call Now</a></div>
        </aside>
      </section>
    </main>
  </div>
  <script src="js/peer-support.js"></script>
</body>
</html>
