// journal.js — loaded by journal.php

const PILL_CLASS = {
  Grateful: 'pill-green',
  Calm: 'pill-blue',
  Hopeful: 'pill-purple',
  Anxious: 'pill-orange',
  Frustrated: 'pill-red',
};

const MAX_PHOTO_BYTES = 2 * 1024 * 1024; // 2MB before base64 overhead

function formatEntryDate(mysqlDatetime) {
  // MySQL DATETIME "YYYY-MM-DD HH:MM:SS" — Safari chokes on that string
  // passed straight to `new Date()`, so swap the space for a "T" first.
  const d = new Date(mysqlDatetime.replace(' ', 'T'));
  return d.toLocaleDateString('default', { day: 'numeric', month: 'long', year: 'numeric' });
}

function entryCardEl(entry) {
  const card = document.createElement('div');
  card.className = 'entry-card';

  const header = document.createElement('header');
  const dateEl = document.createElement('strong');
  dateEl.textContent = formatEntryDate(entry.created_at);
  header.appendChild(dateEl);

  if (entry.emotion) {
    const pill = document.createElement('span');
    pill.className = 'pill ' + (PILL_CLASS[entry.emotion] || 'pill-blue');
    pill.textContent = entry.emotion;
    header.appendChild(pill);
  }

  const body = document.createElement('div');
  body.className = 'muted';
  // Safe here specifically because `entry.content` came back from
  // journal_create / journal_list, both of which only ever return content
  // that has already passed through the server-side allowlist sanitizer
  // (sanitizeJournalHtml in demoAPI.php) before it was stored. Never use
  // innerHTML with content that hasn't gone through that path.
  body.innerHTML = entry.content;

  card.appendChild(header);
  card.appendChild(body);
  return card;
}

async function loadEntries() {
  const list = document.getElementById('entriesList');
  try {
    const res = await fetch('api/demoAPI.php?action=journal_list&limit=5');
    const data = await res.json();

    list.innerHTML = '';
    if (!data.success) {
      list.innerHTML = '<p class="muted">' + (data.message || 'Could not load entries.') + '</p>';
      return;
    }
    if (data.data.entries.length === 0) {
      list.innerHTML = '<p class="muted">No entries yet — write your first one!</p>';
      return;
    }
    data.data.entries.forEach(entry => list.appendChild(entryCardEl(entry)));
  } catch (err) {
    console.error('Failed to load journal entries', err);
    list.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadEntries();

  const editor = document.getElementById('journalContent');
  const saveBtn = document.getElementById('saveEntryBtn');
  const emotionSelect = document.getElementById('emotionSelect');
  const photoInput = document.getElementById('photoInput');
  const addPhotoBtn = document.getElementById('addPhotoBtn');
  const emojiPalette = document.getElementById('emojiPalette');

  // --- Toolbar: bold / italic / bullet / link via execCommand ---
  // execCommand is deprecated but still functions in every major browser;
  // for a project this size it's the practical choice over pulling in a
  // full rich-text editor library.
  //
  // Clicking any element outside the editor — even a non-focusable <span> —
  // triggers the browser's default mousedown behavior, which collapses
  // whatever text selection was active inside the editor. By the time the
  // subsequent 'click' handler ran, the selection was already gone, so
  // bold/italic had nothing left to apply to. Blocking that default on
  // mousedown keeps the selection alive.
  // Bold, implemented manually instead of via execCommand('bold'). Browsers
  // are inconsistent about whether "bold" produces a real <b> tag or an
  // inline font-weight style depending on ambient context, which is why it
  // was unreliable while italic (no such ambiguity) worked fine. This
  // bypasses that entirely: it wraps the actual selection in a real
  // <strong> element, or unwraps it if the selection is already inside one.
  function toggleManualBold() {
    const selection = window.getSelection();
    if (!selection.rangeCount || selection.isCollapsed) {
      alert('Select some text first, then click Bold.');
      return;
    }
    const range = selection.getRangeAt(0);
    if (!editor.contains(range.commonAncestorContainer)) return;

    const startEl = range.commonAncestorContainer.nodeType === 3
      ? range.commonAncestorContainer.parentElement
      : range.commonAncestorContainer;
    const existing = startEl.closest && startEl.closest('strong, b');

    if (existing && editor.contains(existing)) {
      // Already bold — unwrap it
      const parent = existing.parentNode;
      while (existing.firstChild) parent.insertBefore(existing.firstChild, existing);
      parent.removeChild(existing);
    } else {
      const content = range.extractContents();
      const strong = document.createElement('strong');
      strong.appendChild(content);
      range.insertNode(strong);

      selection.removeAllRanges();
      const newRange = document.createRange();
      newRange.selectNodeContents(strong);
      selection.addRange(newRange);
    }
  }

  document.querySelectorAll('.tool-btn[data-cmd]').forEach(btn => {
    btn.addEventListener('mousedown', (e) => e.preventDefault());

    btn.addEventListener('click', (e) => {
      e.preventDefault();
      editor.focus();
      document.execCommand('styleWithCSS', false, false);
      const cmd = btn.dataset.cmd;

      if (cmd === 'bold') {
        toggleManualBold();
      } else if (cmd === 'italic') {
        document.execCommand('italic');
      } else if (cmd === 'bullet') {
        document.execCommand('insertUnorderedList');
      } else if (cmd === 'link') {
        const url = prompt('Link URL (https://...)');
        if (url) {
          if (!/^https?:\/\//i.test(url)) {
            alert('Links must start with http:// or https://');
            return;
          }
          document.execCommand('createLink', false, url);
        }
      } else if (cmd === 'emoji') {
        emojiPalette.style.display = emojiPalette.style.display === 'none' ? 'flex' : 'none';
      }
    });
  });

  emojiPalette.querySelectorAll('[data-emoji]').forEach(btn => {
    btn.addEventListener('mousedown', (e) => e.preventDefault());
    btn.addEventListener('click', () => {
      editor.focus();
      document.execCommand('insertText', false, btn.dataset.emoji);
      emojiPalette.style.display = 'none';
    });
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('[data-cmd="emoji"]')) {
      emojiPalette.style.display = 'none';
    }
  });

  // --- Add Photo ---
  addPhotoBtn.addEventListener('click', () => photoInput.click());

  photoInput.addEventListener('change', () => {
    const file = photoInput.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      alert('Please choose an image file.');
      photoInput.value = '';
      return;
    }
    if (file.size > MAX_PHOTO_BYTES) {
      alert('Image is too large — please choose one under 2MB.');
      photoInput.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = () => {
      editor.focus();
      document.execCommand('insertImage', false, reader.result);
      photoInput.value = '';
    };
    reader.onerror = () => {
      alert('Could not read that image.');
      photoInput.value = '';
    };
    reader.readAsDataURL(file);
  });

  // --- Save ---
  saveBtn.addEventListener('click', async () => {
    const html = editor.innerHTML.trim();
    const plainText = editor.textContent.trim();
    const hasImage = editor.querySelector('img') !== null;

    if (!plainText && !hasImage) {
      alert('Write something (or add a photo) before saving.');
      return;
    }

    const originalLabel = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    try {
      const res = await fetch('api/demoAPI.php?action=journal_create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          content: html,
          emotion: emotionSelect.value,
        }),
      });
      const data = await res.json();

      if (data.success) {
        editor.innerHTML = '';
        emotionSelect.value = '';
        const list = document.getElementById('entriesList');
        const emptyMsg = list.querySelector('p.muted');
        if (emptyMsg) emptyMsg.remove();
        list.insertBefore(entryCardEl(data.data), list.firstChild);
      } else {
        alert(data.message);
      }
    } catch (err) {
      alert('Could not reach the server.');
    } finally {
      saveBtn.disabled = false;
      saveBtn.textContent = originalLabel;
    }
  });
});
