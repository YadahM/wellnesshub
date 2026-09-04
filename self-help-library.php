<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Self-Help Library — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles: reset native <button> chrome for category cards
       and resource items (needed since these are now real buttons, not
       links, so keyboard/AT users get correct semantics for filter actions
       that don't navigate anywhere), plus an active-filter indicator. */
    .category-card{ font:inherit; cursor:pointer; }
    .category-card.active{ border-color:#4b73d9; background:#eef2ff; }
    .resource-item{ font:inherit; cursor:pointer; width:100%; }
    .resource-item[disabled]{ cursor:default; opacity:0.6; }
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
        <a class="active" href="self-help-library.php"><span>▤</span> Self-Help Library</a>
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
          <h1>Self-Help Library</h1>
          <p class="page-subtitle">Browse quick wellness resources and exercises.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="page-grid">
        <article class="panel">
          <div class="panel-heading"><h2>Categories</h2><button class="btn btn-ghost btn-small" type="button" id="clearFilterBtn">Show All</button></div>
          <div class="category-row" id="categoryRow">
            <button class="category-card" type="button" data-category="Anxiety"><span class="category-icon">🧠</span><strong>Anxiety</strong></button>
            <button class="category-card" type="button" data-category="Academic Stress"><span class="category-icon">📚</span><strong>Academic Stress</strong></button>
            <button class="category-card" type="button" data-category="Sleep"><span class="category-icon">🌙</span><strong>Sleep</strong></button>
            <button class="category-card" type="button" data-category="Relationships"><span class="category-icon">❤️</span><strong>Relationships</strong></button>
            <button class="category-card" type="button" data-category="Self Confidence"><span class="category-icon">🪞</span><strong>Self Confidence</strong></button>
          </div>
        </article>
        <article class="panel">
          <div class="panel-heading"><h2 id="resourcesHeading">Popular Resources</h2></div>
          <div class="resource-list" id="resourceList">
            <p class="muted">Loading resources…</p>
          </div>
        </article>
      </section>
    </main>
  </div>
  <script src="js/self-help-library.js"></script>
</body>
</html>
