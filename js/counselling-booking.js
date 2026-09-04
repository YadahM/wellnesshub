// counselling-booking.js — loaded by counselling-booking.php

function formatDateLocal(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`;
}

function formatDisplayDate(dateStr) {
  const d = new Date(dateStr + 'T00:00:00');
  return d.toLocaleDateString('default', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}

let counsellors = [];
let selectedCounsellorId = null;
let selectedDate = null;
let selectedTime = null;

document.addEventListener('DOMContentLoaded', async () => {
  renderCalendar();
  await loadCounsellors();
  await loadCurrentAppointment();

  document.getElementById('counsellorSelect').addEventListener('change', (e) => {
    selectedCounsellorId = e.target.value || null;
    showCounsellorCard();
    selectedTime = null;
    if (selectedCounsellorId && selectedDate) loadSlots();
  });

  document.getElementById('bookBtn').addEventListener('click', bookSession);
  document.getElementById('cancelBtn').addEventListener('click', cancelAppointment);
  document.getElementById('rescheduleBtn').addEventListener('click', async () => {
    if (!confirm('This will cancel your current appointment so you can pick a new time. Continue?')) return;
    await cancelAppointment(true);
  });
});

// --- Counsellors ---
async function loadCounsellors() {
  const select = document.getElementById('counsellorSelect');
  try {
    const res = await fetch('api/demoAPI.php?action=counsellor_list');
    const data = await res.json();
    if (!data.success) {
      select.innerHTML = `<option value="">${data.message}</option>`;
      return;
    }
    counsellors = data.data.counsellors;
    select.innerHTML = '<option value="">Choose a counsellor…</option>' +
      counsellors.map(c => `<option value="${c.counsellor_id}">${c.name}</option>`).join('');
  } catch (err) {
    select.innerHTML = '<option value="">Could not load counsellors</option>';
  }
}

function showCounsellorCard() {
  const card = document.getElementById('counsellorCard');
  const counsellor = counsellors.find(c => String(c.counsellor_id) === String(selectedCounsellorId));
  if (!counsellor) {
    card.style.display = 'none';
    return;
  }
  document.getElementById('counsellorFace').textContent = counsellor.avatar;
  document.getElementById('counsellorName').textContent = counsellor.name;
  document.getElementById('counsellorSpecialties').textContent = counsellor.specialties;
  card.style.display = '';
}

// --- Calendar: current month, weekdays only, no past dates ---
function renderCalendar() {
  const today = new Date();
  const year = today.getFullYear();
  const month = today.getMonth();

  document.getElementById('calendarMonthLabel').textContent =
    today.toLocaleString('default', { month: 'long' }) + ' ' + year;

  const firstWeekday = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const grid = document.getElementById('calendarGrid');

  for (let i = 0; i < firstWeekday; i++) {
    const blank = document.createElement('div');
    blank.className = 'calendar-day';
    grid.appendChild(blank);
  }

  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(year, month, day);
    const dateStr = formatDateLocal(date);
    const weekday = date.getDay(); // 0=Sun, 6=Sat
    const isPast = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
    const isWeekend = weekday === 0 || weekday === 6;
    const isToday = day === today.getDate();

    const cell = document.createElement('button');
    cell.type = 'button';
    cell.className = 'calendar-day' + (isPast || isWeekend ? '' : ' filled') + (isToday ? ' today' : '');
    cell.textContent = day;
    if (isPast || isWeekend) {
      cell.disabled = true;
    } else {
      cell.addEventListener('click', () => selectDate(dateStr, cell));
    }
    grid.appendChild(cell);
  }
}

function selectDate(dateStr, cellEl) {
  document.querySelectorAll('.calendar-day.selected').forEach(el => el.classList.remove('selected'));
  cellEl.classList.add('selected');
  selectedDate = dateStr;
  selectedTime = null;
  document.getElementById('selectedDateLabel').textContent = formatDisplayDate(dateStr);

  if (selectedCounsellorId) {
    loadSlots();
  } else {
    document.getElementById('slotList').innerHTML = '<p class="muted">Choose a counsellor first.</p>';
  }
}

// --- Slots ---
async function loadSlots() {
  const list = document.getElementById('slotList');
  const bookBtn = document.getElementById('bookBtn');
  list.innerHTML = '<p class="muted">Loading slots…</p>';
  bookBtn.disabled = true;

  try {
    const res = await fetch(`api/demoAPI.php?action=slot_list&counsellor_id=${selectedCounsellorId}&date=${selectedDate}`);
    const data = await res.json();
    list.innerHTML = '';

    if (!data.success) {
      list.innerHTML = `<p class="muted">${data.message}</p>`;
      return;
    }
    if (data.data.slots.length === 0) {
      list.innerHTML = '<p class="muted">No slots available on this date.</p>';
      return;
    }

    data.data.slots.forEach(slot => {
      const btn = document.createElement('button');
      btn.className = 'slot';
      btn.textContent = slot.time;
      btn.type = 'button';
      if (!slot.available) {
        btn.disabled = true;
      } else {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.slot.selected').forEach(el => el.classList.remove('selected'));
          btn.classList.add('selected');
          selectedTime = slot.time;
          bookBtn.disabled = false;
        });
      }
      list.appendChild(btn);
    });
  } catch (err) {
    list.innerHTML = '<p class="muted">Could not reach the server.</p>';
  }
}

// --- Book ---
async function bookSession() {
  if (!selectedCounsellorId || !selectedDate || !selectedTime) return;

  const bookBtn = document.getElementById('bookBtn');
  bookBtn.disabled = true;
  bookBtn.textContent = 'Booking...';

  try {
    const res = await fetch('api/demoAPI.php?action=booking_create', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({
        counsellor_id: selectedCounsellorId,
        date: selectedDate,
        time: selectedTime,
      }),
    });
    const data = await res.json();

    if (data.success) {
      await loadCurrentAppointment();
      await loadSlots();
      selectedTime = null;
      bookBtn.textContent = 'Book Session';
    } else {
      alert(data.message);
      bookBtn.disabled = false;
      bookBtn.textContent = 'Book Session';
    }
  } catch (err) {
    alert('Could not reach the server.');
    bookBtn.disabled = false;
    bookBtn.textContent = 'Book Session';
  }
}

// --- Current appointment ---
async function loadCurrentAppointment() {
  const card = document.getElementById('appointmentCard');
  const noMsg = document.getElementById('noAppointmentMsg');

  try {
    const res = await fetch('api/demoAPI.php?action=booking_current');
    const data = await res.json();
    if (!data.success || !data.data.booking) {
      card.style.display = 'none';
      noMsg.style.display = '';
      return;
    }

    const b = data.data.booking;
    document.getElementById('apptFace').textContent = b.avatar;
    document.getElementById('apptDateTime').textContent =
      formatDisplayDate(b.session_date) + ' · ' + b.session_time.substring(0, 5);
    document.getElementById('apptCounsellor').textContent = b.counsellor_name;
    card.dataset.bookingId = b.booking_id;
    card.style.display = '';
    noMsg.style.display = 'none';
  } catch (err) {
    console.error('Failed to load current appointment', err);
  }
}

async function cancelAppointment(isReschedule = false) {
  const card = document.getElementById('appointmentCard');
  const bookingId = card.dataset.bookingId;
  if (!bookingId) return;

  if (!isReschedule && !confirm('Cancel your upcoming appointment?')) return;

  try {
    const res = await fetch('api/demoAPI.php?action=booking_cancel', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ booking_id: bookingId }),
    });
    const data = await res.json();
    if (data.success) {
      await loadCurrentAppointment();
      if (selectedCounsellorId && selectedDate) loadSlots();
    } else {
      alert(data.message);
    }
  } catch (err) {
    alert('Could not reach the server.');
  }
}
