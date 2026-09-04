<?php
/*
 * *** TEMPORARY / INSECURE — DO NOT SHIP LIKE THIS ***
 * This page has no counsellor/admin auth gate because no counsellor login
 * system exists yet in this project. It only reuses the STUDENT session
 * check (auth-check.php) so it isn't wide open to the entire internet, but
 * that means any logged-in student can currently open this URL directly
 * and remove other students' posts. This must be replaced with a real
 * counsellor-role check before this is used for anything beyond local
 * development. See the comment above the moderation_* handlers in
 * demoAPI.php for the same warning.
 */
require_once __DIR__ . '/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Peer Support Moderation (DEV ONLY) — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .dev-warning{ background:#fff1dd; border:2px solid #ad651d; color:#7a4813; padding:14px 18px; border-radius:12px; font-weight:700; margin-bottom:18px; }
    .mod-post-card{ border:1px solid var(--line); border-radius:12px; padding:16px; margin-bottom:14px; }
    .mod-post-card.removed{ opacity:0.5; }
    .mod-actions{ display:flex; gap:10px; margin-top:10px; }
  </style>
</head>
<body class="app-body">
  <div class="app-shell">
    <main class="dashboard-main" style="width:100%;">
      <div class="dev-warning">
        ⚠ DEVELOPMENT ONLY — This page has no counsellor/admin login check.
        Any logged-in student can currently reach this URL directly and act
        on reports. Do not use this beyond local testing until real
        counsellor authentication exists.
      </div>
      <header class="topbar">
        <div>
          <p class="eyebrow">Moderation</p>
          <h1>Reported Posts</h1>
          <p class="page-subtitle">Posts with unreviewed reports from the Peer Support board.</p>
        </div>
      </header>
      <section>
        <div id="queueList"><p class="muted">Loading reported posts…</p></div>
      </section>
    </main>
  </div>
  <script src="js/moderation.js"></script>
</body>
</html>
