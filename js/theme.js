// theme.js — included on every student page. Applies dark mode by reusing
// the .dark-body/.dark-shell/.dark-main/.dark-icon/.dark-avatar classes
// already built for the counsellor side (see css/style.css) — this just
// makes them toggleable instead of hardcoded to one set of pages.

(function () {
  function applyTheme(isDark) {
    document.body.classList.toggle('dark-body', isDark);
    const shell = document.querySelector('.app-shell');
    if (shell) shell.classList.toggle('dark-shell', isDark);
    const main = document.querySelector('.dashboard-main');
    if (main) main.classList.toggle('dark-main', isDark);
    document.querySelectorAll('.icon-btn').forEach(el => el.classList.toggle('dark-icon', isDark));
    document.querySelectorAll('.avatar').forEach(el => el.classList.toggle('dark-avatar', isDark));
  }

  // Fast path: apply a cached preference immediately so repeat visits don't
  // flash light-mode before the real DB setting comes back.
  if (localStorage.getItem('wellnesshub_dark_mode') === '1') {
    applyTheme(true);
  }

  // Authoritative source is always the DB setting, fetched on every load.
  document.addEventListener('DOMContentLoaded', async () => {
    try {
      const res = await fetch('api/demoAPI.php?action=settings_get');
      const data = await res.json();
      if (data.success) {
        applyTheme(data.data.dark_mode);
        localStorage.setItem('wellnesshub_dark_mode', data.data.dark_mode ? '1' : '0');
      }
    } catch (err) {
      // Fail silently — cached/light theme stays as-is.
    }
  });

  // Exposed so settings.php can flip the theme instantly on toggle, without
  // waiting for a page reload.
  window.WellnessHubTheme = { applyTheme };
})();
