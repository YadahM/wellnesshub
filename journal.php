<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your safe space 🔒 — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles for the rich-text editor — same convention as other pages' additions */
    .journal-editable{ min-height:170px; padding:14px; border:1px solid #dde3ee; border-radius:12px; line-height:1.5; overflow-wrap:anywhere; }
    .journal-editable:focus{ outline:none; border-color:var(--green-500); box-shadow:0 0 0 4px rgba(87,166,111,.12); }
    .journal-editable:empty::before{ content:attr(data-placeholder); color:#9aa4b5; }
    .journal-editable img{ max-width:100%; border-radius:8px; margin:8px 0; display:block; }
    .journal-editable b, .journal-editable strong,
    .entry-card b, .entry-card strong{ font-weight:800; }
    .tool-btn{ cursor:pointer; user-select:none; }
    .tool-btn:hover{ opacity:0.7; }
    .emoji-palette{ position:absolute; top:24px; left:0; background:#fff; border:1px solid #dde3ee; border-radius:10px; padding:6px; display:flex; gap:4px; box-shadow:0 8px 20px rgba(0,0,0,.12); z-index:10; }
    .emoji-palette button{ border:none; background:none; font-size:16px; cursor:pointer; padding:4px; border-radius:6px; }
    .emoji-palette button:hover{ background:#f1f3f8; }
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
        <a class="active" href="journal.php"><span>✎</span> Journal</a>
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
          <h1>Your safe space 🔒</h1>
          <p class="page-subtitle">Only you can see your journal entries.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="two-col">
        <article class="panel journal-editor">
          <div class="panel-heading"><div><p class="eyebrow">Private Journal</p><h2>How are you feeling today?</h2></div><span class="pill pill-green">Encrypted</span></div>
          <label class="field">
            <span>Journal Entry</span>
            <div id="journalContent" class="journal-editable" contenteditable="true" data-placeholder="Write your thoughts, worries, goals or reflections here..."></div>
          </label>
          <div class="editor-tools">
            <div class="tool-icons">
              <span class="tool-btn" data-cmd="bold" title="Bold"><b>B</b></span>
              <span class="tool-btn" data-cmd="italic" title="Italic"><i>I</i></span>
              <span class="tool-btn" data-cmd="bullet" title="Bullet list">•</span>
              <span class="tool-btn" data-cmd="emoji" title="Insert emoji" style="position:relative;">☻
                <span id="emojiPalette" class="emoji-palette" style="display:none;">
                  <button type="button" data-emoji="😊">😊</button><button type="button" data-emoji="🙂">🙂</button><button type="button" data-emoji="😌">😌</button><button type="button" data-emoji="😔">😔</button><button type="button" data-emoji="😣">😣</button><button type="button" data-emoji="❤️">❤️</button><button type="button" data-emoji="🌱">🌱</button><button type="button" data-emoji="✨">✨</button>
                </span>
              </span>
              <span class="tool-btn" data-cmd="link" title="Insert link">🔗</span>
            </div>
            <div class="inline">
              <input type="file" id="photoInput" accept="image/*" style="display:none;" />
              <button class="btn btn-ghost btn-small" type="button" id="addPhotoBtn">Add Photo</button>
              <select id="emotionSelect" class="btn btn-ghost btn-small">
                <option value="">Add Emotion</option>
                <option value="Grateful">Grateful</option>
                <option value="Calm">Calm</option>
                <option value="Hopeful">Hopeful</option>
                <option value="Anxious">Anxious</option>
                <option value="Frustrated">Frustrated</option>
              </select>
              <button class="btn btn-primary btn-small" type="button" id="saveEntryBtn">Save Entry</button>
            </div>
          </div>
        </article>
        <article class="panel">
          <div class="panel-heading"><h2>Your Entries</h2><a href="#">View All</a></div>
          <div class="stack" id="entriesList">
            <p class="muted" id="entriesLoading">Loading entries…</p>
          </div>
        </article>
      </section>
    </main>
  </div>
  <script src="js/journal.js"></script>
</body>
</html>
