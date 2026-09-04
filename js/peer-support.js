// peer-support.js — loaded by peer-support.php

const CATEGORY_PILL = {
  Support: 'pill-purple',
  Vent: 'pill-orange',
  Advice: 'pill-blue',
  Encouragement: 'pill-green',
};

let currentTab = 'recent';

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

function postCardEl(post) {
  const card = document.createElement('div');
  card.className = 'post-card';
  card.dataset.postId = post.post_id;

  const meta = document.createElement('div');
  meta.className = 'post-meta';
  const author = document.createElement('span');
  author.textContent = post.is_mine ? 'Anonymous Student (You)' : 'Anonymous Student';
  const pill = document.createElement('span');
  pill.className = 'pill ' + (CATEGORY_PILL[post.category] || 'pill-purple');
  pill.textContent = post.category;
  meta.appendChild(author);
  meta.appendChild(pill);

  const body = document.createElement('p');
  body.textContent = post.content;

  const actions = document.createElement('div');
  actions.className = 'post-actions';

  const likeSpan = document.createElement('span');
  likeSpan.className = 'like-btn' + (post.liked_by_me ? ' liked' : '');
  likeSpan.textContent = (post.liked_by_me ? '♥ ' : '♡ ') + post.like_count;
  likeSpan.addEventListener('click', () => toggleLike(post.post_id, likeSpan));

  const replySpan = document.createElement('span');
  replySpan.textContent = '💬 ' + post.reply_count;
  replySpan.addEventListener('click', () => toggleReplies(post.post_id, card, replySpan));

  const reportSpan = document.createElement('span');
  reportSpan.textContent = 'Report';
  reportSpan.addEventListener('click', () => reportPost(post.post_id, reportSpan));

  const timeSpan = document.createElement('span');
  timeSpan.className = 'muted';
  timeSpan.textContent = timeAgo(post.created_at);

  actions.appendChild(likeSpan);
  actions.appendChild(replySpan);
  actions.appendChild(reportSpan);
  actions.appendChild(timeSpan);

  const thread = document.createElement('div');
  thread.className = 'reply-thread';

  card.appendChild(meta);
  card.appendChild(body);
  card.appendChild(actions);
  card.appendChild(thread);
  return card;
}

async function loadFeed() {
  const feed = document.getElementById('postFeed');
  feed.innerHTML = '<p class="muted">Loading posts…</p>';

  try {
    const res = await fetch('api/demoAPI.php?action=post_list&tab=' + currentTab);
    const data = await res.json();
    feed.innerHTML = '';

    if (!data.success) {
      feed.innerHTML = '<p class="muted">' + data.message + '</p>';
      return;
    }
    if (data.data.posts.length === 0) {
      feed.innerHTML = '<p class="muted">' +
        (currentTab === 'mine' ? "You haven't posted anything yet." : 'No posts yet — be the first to share.') +
        '</p>';
      return;
    }
    data.data.posts.forEach(post => feed.appendChild(postCardEl(post)));
  } catch (err) {
    feed.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

async function toggleLike(postId, likeSpan) {
  try {
    const res = await fetch('api/demoAPI.php?action=post_like', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ post_id: postId }),
    });
    const data = await res.json();
    if (!data.success) {
      alert(data.message);
      return;
    }
    likeSpan.classList.toggle('liked', data.data.liked_by_me);
    likeSpan.textContent = (data.data.liked_by_me ? '♥ ' : '♡ ') + data.data.like_count;
  } catch (err) {
    alert('Could not reach the server.');
  }
}

async function toggleReplies(postId, card, replySpan) {
  const thread = card.querySelector('.reply-thread');
  const isOpen = thread.style.display === 'block';

  if (isOpen) {
    thread.style.display = 'none';
    return;
  }

  thread.style.display = 'block';
  if (thread.dataset.loaded === 'true') return;

  thread.innerHTML = '<p class="muted">Loading replies…</p>';
  try {
    const res = await fetch('api/demoAPI.php?action=post_reply_list&post_id=' + postId);
    const data = await res.json();
    thread.innerHTML = '';

    if (data.success) {
      if (data.data.replies.length === 0) {
        const none = document.createElement('p');
        none.className = 'muted';
        none.textContent = 'No replies yet.';
        thread.appendChild(none);
      } else {
        data.data.replies.forEach(reply => {
          const r = document.createElement('div');
          r.className = 'reply-item';
          r.textContent = 'Anonymous Student: ' + reply.content;
          thread.appendChild(r);
        });
      }
    }

    const inputWrap = document.createElement('div');
    inputWrap.className = 'reply-input';
    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'Write a reply…';
    input.maxLength = 1000;
    const sendBtn = document.createElement('button');
    sendBtn.className = 'btn btn-primary btn-small';
    sendBtn.type = 'button';
    sendBtn.textContent = 'Reply';
    sendBtn.addEventListener('click', async () => {
      const text = input.value.trim();
      if (!text) return;
      sendBtn.disabled = true;
      try {
        const res2 = await fetch('api/demoAPI.php?action=post_reply_create', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ post_id: postId, content: text }),
        });
        const data2 = await res2.json();
        if (data2.success) {
          const r = document.createElement('div');
          r.className = 'reply-item';
          r.textContent = 'Anonymous Student (You): ' + data2.data.content;
          thread.insertBefore(r, inputWrap);
          input.value = '';
          const currentCount = parseInt(replySpan.textContent.replace('💬 ', ''), 10) || 0;
          replySpan.textContent = '💬 ' + (currentCount + 1);
        } else {
          alert(data2.message);
        }
      } catch (err) {
        alert('Could not reach the server.');
      } finally {
        sendBtn.disabled = false;
      }
    });

    inputWrap.appendChild(input);
    inputWrap.appendChild(sendBtn);
    thread.appendChild(inputWrap);

    thread.dataset.loaded = 'true';
  } catch (err) {
    thread.innerHTML = '<p class="muted">Could not load replies.</p>';
  }
}

async function reportPost(postId, reportSpan) {
  if (!confirm('Report this post to moderators?')) return;
  try {
    const res = await fetch('api/demoAPI.php?action=post_report', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ post_id: postId }),
    });
    const data = await res.json();
    alert(data.message);
    if (data.success) {
      reportSpan.textContent = 'Reported';
      reportSpan.style.pointerEvents = 'none';
      reportSpan.style.opacity = '0.5';
    }
  } catch (err) {
    alert('Could not reach the server.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadFeed();

  document.querySelectorAll('#feedTabs .tab-btn').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('#feedTabs .tab-btn').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentTab = tab.dataset.tab;
      loadFeed();
    });
  });

  const postBtn = document.getElementById('postBtn');
  const contentEl = document.getElementById('postContent');
  const categoryEl = document.getElementById('postCategory');

  postBtn.addEventListener('click', async () => {
    const content = contentEl.value.trim();
    if (!content) {
      alert('Write something before posting.');
      return;
    }

    postBtn.disabled = true;
    postBtn.textContent = 'Posting...';

    try {
      const res = await fetch('api/demoAPI.php?action=post_create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ content: content, category: categoryEl.value }),
      });
      const data = await res.json();

      if (data.success) {
        contentEl.value = '';
        await loadFeed();
      } else {
        alert(data.message);
      }
    } catch (err) {
      alert('Could not reach the server.');
    } finally {
      postBtn.disabled = false;
      postBtn.textContent = 'Post Anonymously';
    }
  });
});
