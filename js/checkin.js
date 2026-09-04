// checkin.js — loaded by dashboard.php via <script src="js/checkin.js"></script>

document.addEventListener('DOMContentLoaded', () => {
  const moodButtons  = document.querySelectorAll('.mood-row .mood');
  const checkinBtn   = document.getElementById('checkinBtn');
  const slider       = document.getElementById('stressSlider');
  const stressValue  = document.getElementById('stressValue');
  const donut        = document.getElementById('moodDonut');
  const emptyState   = document.getElementById('summaryEmptyState');

  let selectedMood = document.querySelector('.mood-row .mood.active')?.dataset.mood || 'great';

  // --- Mood buttons ---
  moodButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      moodButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      selectedMood = btn.dataset.mood;
    });
  });

  // --- Real stress slider ---
  slider.addEventListener('input', () => {
    stressValue.textContent = slider.value;
  });

  // --- Submit check-in ---
  checkinBtn.addEventListener('click', async () => {
    const originalLabel = checkinBtn.textContent;
    checkinBtn.disabled = true;
    checkinBtn.textContent = 'Saving...';

    try {
      const res = await fetch('api/demoAPI.php?action=checkin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          mood: selectedMood,
          stress_level: slider.value,
        }),
      });
      const data = await res.json();

      if (data.success) {
        checkinBtn.textContent = 'Saved ✓';
        loadWeeklySummary(); // refresh the donut immediately
        setTimeout(() => {
          checkinBtn.textContent = originalLabel;
          checkinBtn.disabled = false;
        }, 1500);
      } else {
        checkinBtn.textContent = originalLabel;
        checkinBtn.disabled = false;
        alert(data.message);
      }
    } catch (err) {
      checkinBtn.textContent = originalLabel;
      checkinBtn.disabled = false;
      alert('Could not reach the server.');
    }
  });

  // --- Weekly summary donut ---
  async function loadWeeklySummary() {
    try {
      const res = await fetch('api/demoAPI.php?action=weekly_mood_summary');
      const data = await res.json();
      if (!data.success) return;

      const pct = data.data.percentages; // {calm, neutral, low, risk}

      document.getElementById('pct-calm').textContent = pct.calm;
      document.getElementById('pct-neutral').textContent = pct.neutral;
      document.getElementById('pct-low').textContent = pct.low;
      document.getElementById('pct-risk').textContent = pct.risk;

      emptyState.style.display = data.data.total_checkins === 0 ? 'block' : 'none';

      // Pull actual colors from the legend dots so the donut matches the
      // team's existing CSS instead of guessing hex values.
      const colorOf = cls => getComputedStyle(document.querySelector('.legend-dot.' + cls)).backgroundColor;
      const colors = {
        calm: colorOf('calm'),
        neutral: colorOf('neutral'),
        low: colorOf('low'),
        risk: colorOf('risk'),
      };

      let acc = 0;
      const stops = [];
      ['calm', 'neutral', 'low', 'risk'].forEach(key => {
        const start = acc;
        acc += pct[key];
        stops.push(`${colors[key]} ${start}% ${acc}%`);
      });

      donut.style.background = (pct.calm + pct.neutral + pct.low + pct.risk === 0)
        ? '#e5e7eb' // flat grey when there's no data at all
        : `conic-gradient(${stops.join(', ')})`;
      donut.style.borderRadius = '50%';
    } catch (err) {
      // Summary is a nice-to-have on page load; fail silently rather than
      // blocking the rest of the dashboard.
      console.error('Could not load weekly summary', err);
    }
  }

  loadWeeklySummary();
});
