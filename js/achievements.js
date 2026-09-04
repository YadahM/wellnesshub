// achievements.js — loaded by achievements.php

document.addEventListener('DOMContentLoaded', async () => {
  try {
    const res = await fetch('api/demoAPI.php?action=achievements_summary');
    const data = await res.json();
    if (!data.success) {
      document.getElementById('badgeGrid').innerHTML = '<p class="muted">' + data.message + '</p>';
      return;
    }

    const d = data.data;

    document.getElementById('levelLabel').textContent = 'Level ' + d.level;
    document.getElementById('xpBar').style.width = Math.round((d.xp_into_level / d.xp_per_level) * 100) + '%';
    document.getElementById('xpLabel').textContent = `${d.xp_into_level} / ${d.xp_per_level} XP`;

    const badgeGrid = document.getElementById('badgeGrid');
    badgeGrid.innerHTML = '';
    d.badges.forEach(b => {
      const card = document.createElement('div');
      card.className = 'badge-card' + (b.unlocked ? '' : ' locked');
      card.innerHTML = `
        <span class="badge-icon">${b.unlocked ? b.icon : '🔒'}</span>
        <h3>${b.name}</h3>
        <p class="muted">${b.unlocked ? 'Unlocked' : 'Locked'}</p>
      `;
      badgeGrid.appendChild(card);
    });

    const statGrid = document.getElementById('statGrid');
    const resourcesLabel = d.stats.resources_used === null ? '—' : d.stats.resources_used;
    statGrid.innerHTML = `
      <div class="stat-mini"><strong>${d.stats.checkins}</strong><span>Check-ins</span></div>
      <div class="stat-mini"><strong>${d.stats.journal_entries}</strong><span>Journal Entries</span></div>
      <div class="stat-mini"><strong>${resourcesLabel}</strong><span>Resources Used</span></div>
      <div class="stat-mini"><strong>${d.stats.days_active}</strong><span>Days Active</span></div>
    `;
  } catch (err) {
    console.error('Failed to load achievements', err);
    document.getElementById('badgeGrid').innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
});
