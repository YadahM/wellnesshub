<?php require_once __DIR__ . '/auth-check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Counselling Booking — WellnessHub</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    /* Supplementary styles — button-reset for calendar days (now real
       buttons for keyboard/click access) and a disabled-slot state */
    .calendar-day{ font:inherit; cursor:pointer; }
    .calendar-day[disabled]{ cursor:default; opacity:0.35; }
    .calendar-day.selected{ outline:2px solid #4b73d9; outline-offset:-2px; }
    .slot[disabled]{ opacity:0.4; cursor:not-allowed; text-decoration:line-through; }
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
        <a class="active" href="counselling-booking.php"><span>♡</span> Book Counselling</a>
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
          <h1>Counselling Booking</h1>
          <p class="page-subtitle">Book, reschedule or cancel a counselling session.</p>
        </div>
        <div class="topbar-actions">
          <a class="icon-btn" href="notifications.php" aria-label="Notifications">🔔</a>
          <?php $firstName = explode(' ', $_SESSION['full_name'])[0]; ?>
          <div class="avatar"><?= htmlspecialchars(strtoupper(substr($firstName, 0, 1))) ?></div>
        </div>
      </header>
      <section class="booking-layout">
        <article class="panel">
          <div class="panel-heading"><h2>Book a Counselling Session</h2></div>
          <label class="field"><span>Select Counsellor</span><select id="counsellorSelect"><option value="">Loading counsellors…</option></select></label>
          <div class="counsellor-card" id="counsellorCard" style="display:none;"><div class="face" id="counsellorFace"></div><div><h3 id="counsellorName"></h3><p class="muted" id="counsellorSpecialties"></p></div></div>
        </article>
        <article class="panel">
          <div class="calendar-header"><h2 id="calendarMonthLabel">Loading…</h2><span class="pill pill-blue">Select Date</span></div>
          <div class="calendar-grid" id="calendarGrid">
            <div class="day-name">Sun</div><div class="day-name">Mon</div><div class="day-name">Tue</div><div class="day-name">Wed</div><div class="day-name">Thu</div><div class="day-name">Fri</div><div class="day-name">Sat</div>
          </div>
        </article>
        <article class="panel">
          <h2>Available Slots</h2>
          <p class="muted" id="selectedDateLabel">Pick a counsellor and a date to see available times.</p>
          <div class="slot-list" id="slotList"></div>
          <button class="btn btn-primary btn-full" style="margin-top:14px" id="bookBtn" disabled>Book Session</button>

          <div class="appointment-card" style="margin-top:16px" id="appointmentCard" style="display:none;">
            <p class="eyebrow">Upcoming Appointment</p>
            <div class="inline"><div class="face green-face" id="apptFace"></div><div><strong id="apptDateTime"></strong><p class="muted" id="apptCounsellor"></p></div></div>
            <div class="inline">
              <button class="btn btn-ghost btn-small" type="button" id="rescheduleBtn">Reschedule</button>
              <button class="btn btn-danger btn-small" type="button" id="cancelBtn">Cancel</button>
            </div>
          </div>
          <p class="muted" id="noAppointmentMsg" style="margin-top:16px;">No upcoming appointment.</p>
        </article>
      </section>
    </main>
  </div>
  <script src="js/counselling-booking.js"></script>
</body>
</html>
