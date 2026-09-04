// self-help-library.js — loaded by self-help-library.php

async function loadResources(category) {
  const list = document.getElementById('resourceList');
  const heading = document.getElementById('resourcesHeading');
  list.innerHTML = '<p class="muted">Loading resources…</p>';
  heading.textContent = category ? category + ' Resources' : 'Popular Resources';

  try {
    const url = 'api/demoAPI.php?action=resource_list' + (category ? '&category=' + encodeURIComponent(category) : '');
    const res = await fetch(url);
    const data = await res.json();

    list.innerHTML = '';
    if (!data.success) {
      list.innerHTML = '<p class="muted">' + (data.message || 'Could not load resources.') + '</p>';
      return;
    }
    if (data.data.resources.length === 0) {
      list.innerHTML = '<p class="muted">No resources in this category yet.</p>';
      return;
    }
    data.data.resources.forEach(r => list.appendChild(resourceItemEl(r)));
  } catch (err) {
    console.error('Failed to load resources', err);
    list.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

function resourceItemEl(resource) {
  // Only the crisis contact has a real destination right now (a tel: link).
  // Everything else has no file/audio storage behind it yet, so rather than
  // pretend these open something, they're shown as non-interactive with an
  // honest "Coming soon" label instead of a dead click.
  const isLive = !!resource.url;
  const el = document.createElement(isLive ? 'a' : 'button');
  el.className = 'resource-item';
  if (isLive) {
    el.href = resource.url;
    if (resource.resource_type !== 'contact') {
      el.target = '_blank';
      el.rel = 'noopener noreferrer';
    }
  } else {
    el.type = 'button';
    el.disabled = true;
  }

  const thumb = document.createElement('span');
  thumb.className = 'resource-thumb';
  thumb.textContent = resource.icon;

  const textWrap = document.createElement('div');
  const h3 = document.createElement('h3');
  h3.textContent = resource.title;
  const p = document.createElement('p');
  p.textContent = isLive ? resource.subtitle : resource.subtitle + ' · Coming soon';

  textWrap.appendChild(h3);
  textWrap.appendChild(p);

  const chevron = document.createElement('span');
  chevron.textContent = isLive ? '›' : '';

  el.appendChild(thumb);
  el.appendChild(textWrap);
  el.appendChild(chevron);
  return el;
}

document.addEventListener('DOMContentLoaded', () => {
  loadResources(null);

  const cards = document.querySelectorAll('.category-card');
  cards.forEach(card => {
    card.addEventListener('click', () => {
      cards.forEach(c => c.classList.remove('active'));
      card.classList.add('active');
      loadResources(card.dataset.category);
    });
  });

  document.getElementById('clearFilterBtn').addEventListener('click', () => {
    cards.forEach(c => c.classList.remove('active'));
    loadResources(null);
  });
});
