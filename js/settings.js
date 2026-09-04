// settings.js — loaded by settings.php

const switches = {
  dark_mode: document.getElementById('switchDarkMode'),
  booking_reminders: document.getElementById('switchBookingReminders'),
  weekly_mood_email: document.getElementById('switchWeeklyEmail'),
};

let current = { dark_mode: false, booking_reminders: true, weekly_mood_email: true };

function renderSwitches() {
  Object.entries(switches).forEach(([key, el]) => {
    el.classList.toggle('on', current[key]);
  });
}

async function loadSettings() {
  try {
    const res = await fetch('api/demoAPI.php?action=settings_get');
    const data = await res.json();
    if (data.success) {
      current = data.data;
      renderSwitches();
    }
  } catch (err) {
    console.error('Failed to load settings', err);
  }
}

async function saveSettings() {
  try {
    const res = await fetch('api/demoAPI.php?action=settings_update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        dark_mode: current.dark_mode ? '1' : '0',
        booking_reminders: current.booking_reminders ? '1' : '0',
        weekly_mood_email: current.weekly_mood_email ? '1' : '0',
      }),
    });
    const data = await res.json();
    if (!data.success) alert(data.message);
  } catch (err) {
    alert('Could not reach the server.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadSettings();

  Object.entries(switches).forEach(([key, el]) => {
    el.addEventListener('click', () => {
      current[key] = !current[key];
      renderSwitches();
      saveSettings();

      if (key === 'dark_mode' && window.WellnessHubTheme) {
        window.WellnessHubTheme.applyTheme(current.dark_mode);
        localStorage.setItem('wellnesshub_dark_mode', current.dark_mode ? '1' : '0');
      }
    });
  });
});
