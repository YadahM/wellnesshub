// mood-tracking.js — loaded by mood-tracking.php

const MOOD_EMOJI = {
  great: '😊',
  good: '🙂',
  okay: '😐',
  low: '😟',
  struggling: '😣',
};
const MOOD_SCORE = { struggling: 1, low: 2, okay: 3, good: 4, great: 5 };

function formatDateLocal(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

document.addEventListener('DOMContentLoaded', async () => {
  setupTabs();
  try {
    const res = await fetch('api/demoAPI.php?action=mood_history');
    const data = await res.json();
    if (!data.success) {
      blocks.charts.querySelector('#insightText').textContent = data.message || 'Could not load mood history.';
      return;
    }
    renderCalendar(data.data.calendar);
    renderWeek(data.data.week);
  } catch (err) {
    console.error('Failed to load mood history', err);
    blocks.charts.querySelector('#insightText').textContent = 'Could not reach the server.';
  }
});

// --- Calendar ---
function renderCalendar(calendarRows) {
  const today = new Date();
  const year = today.getFullYear();
  const month = today.getMonth(); // 0-indexed

  blocks.calendar.querySelector('#calendarMonthLabel').textContent =
    today.toLocaleString('default', { month: 'long' }) + ' ' + year;

  const moodByDay = {};
  calendarRows.forEach(row => { moodByDay[row.day] = row.mood; });

  const firstWeekday = new Date(year, month, 1).getDay(); // 0=Sun
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const grid = blocks.calendar.querySelector('#calendarGrid');

  // Leading blanks so day 1 lands on the correct weekday column
  for (let i = 0; i < firstWeekday; i++) {
    const blank = document.createElement('div');
    blank.className = 'calendar-day';
    grid.appendChild(blank);
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const cell = document.createElement('div');
    const mood = moodByDay[day];
    cell.className = 'calendar-day' + (mood ? ' filled' : '') + (day === today.getDate() ? ' today' : '');
    cell.innerHTML = `${day}<span class="emoji">${mood ? MOOD_EMOJI[mood] : ''}</span>`;
    grid.appendChild(cell);
  }
}

// --- Weekly trend line + stress bars ---
function renderWeek(weekRows) {
  // Build 7 fixed slots: today-6 .. today, each carrying real data if present
  const byDate = {};
  weekRows.forEach(row => { byDate[row.checkin_date] = row; });

  const slots = [];
  for (let i = 6; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    const key = formatDateLocal(d);
    slots.push({
      label: d.toLocaleDateString('default', { weekday: 'short' }),
      data: byDate[key] || null,
    });
  }

  renderTrendChart(slots);
  renderStressBars(slots);
  renderInsight(slots);
  renderStats(slots);
}

function renderTrendChart(slots) {
  const xFor = i => 26 + i * 58.67;
  const yFor = score => 150 - ((score - 1) / 4) * 110; // score 1→150(bottom), 5→40(top)

  const linesGroup = blocks.charts.querySelector('#chartLines');
  const dotsGroup = blocks.charts.querySelector('#chartDots');
  const labelsGroup = blocks.charts.querySelector('#chartLabels');
  linesGroup.innerHTML = '';
  dotsGroup.innerHTML = '';
  labelsGroup.innerHTML = '';

  const points = slots.map((slot, i) => ({
    x: xFor(i),
    y: slot.data ? yFor(slot.data.mood_score) : null,
  }));

  // Connect only consecutive days that both have data — no invented gaps
  for (let i = 0; i < points.length - 1; i++) {
    if (points[i].y !== null && points[i + 1].y !== null) {
      const line = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      line.setAttribute('class', 'chart-line');
      line.setAttribute('d', `M${points[i].x} ${points[i].y} L${points[i + 1].x} ${points[i + 1].y}`);
      linesGroup.appendChild(line);
    }
  }

  points.forEach((p, i) => {
    if (p.y !== null) {
      const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      dot.setAttribute('class', 'chart-dot');
      dot.setAttribute('cx', p.x);
      dot.setAttribute('cy', p.y);
      dot.setAttribute('r', 5);
      const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
      title.textContent = `${slots[i].label}: ${slots[i].data.mood}`;
      dot.appendChild(title);
      dotsGroup.appendChild(dot);
    }
    const label = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    label.setAttribute('class', 'chart-label');
    label.setAttribute('x', p.x - 2);
    label.setAttribute('y', 175);
    label.textContent = slots[i].label;
    labelsGroup.appendChild(label);
  });
}

function renderStressBars(slots) {
  const container = blocks.charts.querySelector('#stressBarChart');
  container.innerHTML = '';

  slots.forEach(slot => {
    const item = document.createElement('div');
    item.className = 'bar-item';

    const bar = document.createElement('div');
    if (slot.data) {
      bar.className = 'bar';
      bar.style.height = (slot.data.stress_level * 10) + '%';
      bar.title = `Stress level: ${slot.data.stress_level}/10`;
    } else {
      bar.className = 'bar no-data';
      bar.style.height = '4%';
      bar.title = 'No check-in';
    }

    const label = document.createElement('span');
    label.textContent = slot.label;

    item.appendChild(bar);
    item.appendChild(label);
    container.appendChild(item);
  });
}

function renderInsight(slots) {
  const present = slots.map((s, i) => (s.data ? { i, score: s.data.mood_score } : null)).filter(Boolean);
  const insightEl = blocks.charts.querySelector('#insightText');

  if (present.length < 2) {
    insightEl.textContent = 'Not enough check-ins this week yet to show a trend — try checking in daily.';
    return;
  }

  const mid = Math.ceil(present.length / 2);
  const firstHalf = present.slice(0, mid);
  const secondHalf = present.slice(mid);
  const avg = arr => arr.reduce((sum, p) => sum + p.score, 0) / arr.length;

  const diff = (secondHalf.length ? avg(secondHalf) : avg(firstHalf)) - avg(firstHalf);

  if (diff > 0.4) {
    insightEl.textContent = 'Your mood looks like it\'s trending upward this week.';
  } else if (diff < -0.4) {
    insightEl.textContent = 'Your mood looks like it\'s dipped a bit this week — support is here if you need it.';
  } else {
    insightEl.textContent = 'Your mood has been fairly steady this week.';
  }
}

// --- Tabs (two-slot swap: clicked view takes the left slot, whatever
// was on the left moves to the right slot) ---
let currentLeft = 'calendar';
let currentRight = 'charts';
const blocks = {};

function initBlocks() {
  ['calendar', 'charts', 'stats'].forEach(name => {
    const tpl = document.getElementById('block' + name[0].toUpperCase() + name.slice(1));
    const node = document.createElement('div');
    node.appendChild(tpl.content.cloneNode(true));
    blocks[name] = node;
  });
}

function updateActiveButton() {
  document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.tab === currentLeft);
  });
}

function applySlots() {
  const leftPanel = document.getElementById('leftPanel');
  const rightPanel = document.getElementById('rightPanel');
  leftPanel.innerHTML = '';
  rightPanel.innerHTML = '';
  leftPanel.appendChild(blocks[currentLeft]);
  rightPanel.appendChild(blocks[currentRight]);
  updateActiveButton();
}

function setupTabs() {
  initBlocks();
  applySlots(); // default: calendar left, charts right

  document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.addEventListener('click', () => {
      const clicked = btn.dataset.tab;
      if (clicked === currentLeft) return; // already on the left, no-op
      currentRight = currentLeft;
      currentLeft = clicked;
      applySlots();
    });
  });
}

// --- Stats ---
function renderStats(slots) {
  const present = slots.filter(s => s.data);

  blocks.stats.querySelector('#statCheckins').textContent = `${present.length}/7`;

  if (present.length === 0) {
    blocks.stats.querySelector('#statAvgMood').textContent = '–';
    blocks.stats.querySelector('#statAvgStress').textContent = '–';
    return;
  }

  const avgMood = present.reduce((sum, s) => sum + s.data.mood_score, 0) / present.length;
  const avgStress = present.reduce((sum, s) => sum + s.data.stress_level, 0) / present.length;

  blocks.stats.querySelector('#statAvgMood').textContent = avgMood.toFixed(1);
  blocks.stats.querySelector('#statAvgStress').textContent = avgStress.toFixed(1);
}
