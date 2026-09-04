// moderation.js — loaded by peer-support-moderation.php

function postCardEl(post) {
  const card = document.createElement('div');
  card.className = 'mod-post-card' + (post.is_removed ? ' removed' : '');

  const meta = document.createElement('div');
  meta.className = 'post-meta';

  const reportPill = document.createElement('span');
  reportPill.className = 'pill pill-red';
  reportPill.textContent = `${post.pending_report_count} report${post.pending_report_count === 1 ? '' : 's'}`;
  meta.appendChild(reportPill);

  const categoryPill = document.createElement('span');
  categoryPill.className = 'pill pill-purple';
  categoryPill.textContent = post.category;
  meta.appendChild(categoryPill);

  if (post.is_removed) {
    const removedPill = document.createElement('span');
    removedPill.className = 'pill pill-orange';
    removedPill.textContent = 'Already removed';
    meta.appendChild(removedPill);
  }

  const body = document.createElement('p');
  body.textContent = post.content; // textContent — never innerHTML — for user-authored text

  card.appendChild(meta);
  card.appendChild(body);

  const actions = document.createElement('div');
  actions.className = 'mod-actions';

  const dismissBtn = document.createElement('button');
  dismissBtn.className = 'btn btn-ghost btn-small';
  dismissBtn.textContent = 'Dismiss Reports';
  dismissBtn.addEventListener('click', () => actOnPost(post.post_id, 'moderation_dismiss', card));

  const removeBtn = document.createElement('button');
  removeBtn.className = 'btn btn-danger btn-small';
  removeBtn.textContent = post.is_removed ? 'Already Removed' : 'Remove Post';
  removeBtn.disabled = post.is_removed;
  removeBtn.addEventListener('click', () => {
    if (!confirm('Remove this post from the board? This cannot be undone from here.')) return;
    actOnPost(post.post_id, 'moderation_remove', card);
  });

  actions.appendChild(dismissBtn);
  actions.appendChild(removeBtn);
  card.appendChild(actions);
  return card;
}

async function actOnPost(postId, action, cardEl) {
  try {
    const res = await fetch('api/demoAPI.php?action=' + action, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ post_id: postId }),
    });
    const data = await res.json();
    if (data.success) {
      cardEl.remove(); // handled — drop it from the pending queue view
    } else {
      alert(data.message);
    }
  } catch (err) {
    alert('Could not reach the server.');
  }
}

async function loadQueue() {
  const list = document.getElementById('queueList');
  try {
    const res = await fetch('api/demoAPI.php?action=moderation_queue');
    const data = await res.json();
    list.innerHTML = '';

    if (!data.success) {
      list.innerHTML = '<p class="muted">' + data.message + '</p>';
      return;
    }
    if (data.data.posts.length === 0) {
      list.innerHTML = '<p class="muted">No pending reports. Queue is clear.</p>';
      return;
    }
    data.data.posts.forEach(post => list.appendChild(postCardEl(post)));
  } catch (err) {
    list.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

document.addEventListener('DOMContentLoaded', loadQueue);
