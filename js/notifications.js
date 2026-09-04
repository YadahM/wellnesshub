// notifications.js — loaded by notifications.php

const TYPE_ICON = {
  booking_created: '💬',
  booking_cancelled: '🚫',
};
const MOOD_SCORE = { struggling: 1, low: 2, okay: 3, good: 4, great: 5 };

function timeAgo(mysqlDatetime) {
  const then = new Date(mysqlDatetime.replace(' ', 'T'));
  const diffMs = Date.now() - then.getTime();
  const mins = Math.floor(diffMs / 60000);
  if (mins < 1) return 'Just now';
  if (mins < 60) return mins + 'm ago';
  const hours = Math.floor(mins / 60);
  if (hours < 24) return hours + 'h ago';
  const days = Math.floor(hours / 24);
  if (days < 7) return days + 'd ago';
  return then.toLocaleDateString('default', { day: 'numeric', month: 'short' });
}

function noticeEl(n) {
  const el = document.createElement('div');
  el.className = 'notice' + (n.is_read ? '' : ' unread');

  const icon = document.createElement('span');
  icon.className = 'notice-icon';
  icon.textContent = TYPE_ICON[n.type] || '🔔';

  const textWrap = document.createElement('div');
  const strong = document.createElement('strong');
  strong.textContent = n.title;
  const p = document.createElement('p');
  p.className = 'muted';
  p.textContent = n.message;
  textWrap.appendChild(strong);
  textWrap.appendChild(p);

  const time = document.createElement('span');
  time.textContent = timeAgo(n.created_at);

  el.appendChild(icon);
  el.appendChild(textWrap);
  el.appendChild(time);
  return el;
}

async function loadNotifications() {
  const list = document.getElementById('notificationList');
  try {
    const res = await fetch('api/demoAPI.php?action=notification_list');
    const data = await res.json();
    list.innerHTML = '';

    if (!data.success) {
      list.innerHTML = '<p class="muted">' + data.message + '</p>';
      return;
    }
    if (data.data.notifications.length === 0) {
      list.innerHTML = '<p class="muted">No notifications yet.</p>';
      return;
    }
    data.data.notifications.forEach(n => list.appendChild(noticeEl(n)));
  } catch (err) {
    list.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

// Weekly email preview built from real data (weekly_mood_summary +
// mood_history) rather than a fabricated "improved by X%" figure.
async function loadEmailPreview() {
  const body = document.getElementById('emailBody');
  const donut = document.getElementById('emailDonut');

  try {
    const [summaryRes, historyRes] = await Promise.all([
      fetch('api/demoAPI.php?action=weekly_mood_summary'),
      fetch('api/demoAPI.php?action=mood_history'),
    ]);
    const summary = await summaryRes.json();
    const history = await historyRes.json();

    if (!summary.success) {
      body.textContent = summary.message || 'Could not load your weekly summary.';
      return;
    }

    const totalCheckins = summary.data.total_checkins;

    let trendPhrase = 'not enough check-ins yet to show a trend';
    if (history.success) {
      const present = history.data.week.filter(w => w.mood_score != null);
      if (present.length >= 2) {
        const mid = Math.ceil(present.length / 2);
        const avg = arr => arr.reduce((s, w) => s + w.mood_score, 0) / arr.length;
        const diff = avg(present.slice(mid)) - avg(present.slice(0, mid));
        trendPhrase = diff > 0.4 ? 'trending upward' : diff < -0.4 ? 'dipped a little' : 'fairly steady';
      }
    }

    body.textContent = `You completed ${totalCheckins} check-in${totalCheckins === 1 ? '' : 's'} this week. Your mood looks ${trendPhrase}.`;

    // Simple progress ring: proportion of the week (out of 7 days) checked in.
    const fraction = Math.min(totalCheckins / 7, 1);
    donut.style.background = totalCheckins === 0
      ? '#e5e7eb'
      : `conic-gradient(#4b73d9 0% ${fraction * 100}%, #e5e7eb ${fraction * 100}% 100%)`;
    donut.style.borderRadius = '50%';
  } catch (err) {
    body.textContent = 'Could not reach the server.';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadNotifications();
  loadEmailPreview();

  document.getElementById('markReadBtn').addEventListener('click', async () => {
    try {
      const res = await fetch('api/demoAPI.php?action=notification_mark_all_read', { method: 'POST' });
      const data = await res.json();
      if (data.success) {
        document.querySelectorAll('.notice.unread').forEach(el => el.classList.remove('unread'));
      }
    } catch (err) {
      alert('Could not reach the server.');
    }
  });
});
