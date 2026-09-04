<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reports & Export — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body class="app-body admin-body">
  <div class="app-shell admin-shell">
    <aside class="sidebar admin-sidebar">
      <a class="brand-mini sidebar-brand" href="index.php">
        <span class="mini-leaf" aria-hidden="true">☘</span>
        <span>WellnessHub</span>
      </a>

      <nav class="side-nav" aria-label="Admin navigation">
        <a href="admin-dashboard.php"><span>▣</span> Dashboard</a>
        <a href="admin-users.php"><span>👥</span> Users</a>
        <a href="admin-counsellors.php"><span>♡</span> Counsellors</a>
        <a class="active" href="admin-reports.php"><span>📊</span> Reports</a>
        <a href="system-logs.php"><span>☷</span> System Logs</a>
        <a href="admin-settings.php"><span>⚙</span> Settings</a>
        <a href="login.php"><span>↪</span> Logout</a>
      </nav>
    </aside>

    <main class="dashboard-main admin-main ">
      <header class="topbar admin-topbar">
        <div>
          <p class="eyebrow">Admin Side</p>
          <h1>Reports & Export</h1>
          <p class="page-subtitle">Generate and export institutional wellness reports.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn admin-icon" href="system-logs.php" aria-label="System logs">🔔</a>
          <div class="avatar admin-avatar">A</div>
        </div>
      </header>
      <section class="page-grid"><article class="panel"><div class="panel-heading"><h2>Institution Wellness Report</h2><a class="btn btn-purple btn-small" href="#">Generate Report</a></div><div class="three-col"><label class="field"><span>Report Type</span><select><option>Monthly Report</option><option>Risk Report</option><option>Engagement Report</option></select></label><label class="field"><span>Month</span><select><option>May 2026</option><option>June 2026</option></select></label><label class="field"><span>Department</span><select><option>All Departments</option><option>DIT</option><option>BAS</option><option>NET</option></select></label></div></article><article class="panel"><h2>Report Summary</h2><div class="report-summary"><div class="report-card"><span>Average Mood</span><strong>2.8 / 5.0</strong><small class="green">+ 8% from Apr</small></div><div class="report-card"><span>High Risk Students</span><strong>18</strong><small class="red">- 5% from Apr</small></div><div class="report-card"><span>Counselling Sessions</span><strong>320</strong><small class="green">+ 12% from Apr</small></div></div><div class="inline" style="margin-top:18px"><button class="btn btn-purple">Export as PDF</button><button class="btn btn-light">Export as CSV</button></div></article></section>
    </main>
  </div>
</body>
</html>
