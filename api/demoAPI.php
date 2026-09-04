<?php
// This file NEVER outputs HTML. It is called only via fetch() from
// register.php (?action=register) and login.php (?action=login).
// header() and session_start() are safe here because nothing is echoed before them.

session_start();
header('Content-Type: application/json');

// demoDB.php lives in the Demo/ root, one level up from here.
require_once __DIR__ . '/../demoDB.php';

$action = $_GET['action'] ?? '';

match ($action) {
    'register' => handleRegister($pdo),
    'login'    => handleLogin($pdo),
    'checkin'  => handleCheckin($pdo),  
	'mood_history' => handleMoodHistory($pdo),
    'weekly_mood_summary' => handleWeeklyMoodSummary($pdo),
    'journal_create' => handleJournalCreate($pdo),
    'journal_list' => handleJournalList($pdo),
    'resource_list' => handleResourceList($pdo),
    'counsellor_list' => handleCounsellorList($pdo),
    'slot_list' => handleSlotList($pdo),
    'booking_create' => handleBookingCreate($pdo),
    'booking_current' => handleBookingCurrent($pdo),
    'booking_cancel' => handleBookingCancel($pdo),
    'post_create' => handlePostCreate($pdo),
    'post_list' => handlePostList($pdo),
    'post_like' => handlePostLike($pdo),
    'post_reply_create' => handlePostReplyCreate($pdo),
    'post_reply_list' => handlePostReplyList($pdo),
    'post_report' => handlePostReport($pdo),
    'achievements_summary' => handleAchievementsSummary($pdo),
    'notification_list' => handleNotificationList($pdo),
    'notification_mark_all_read' => handleNotificationMarkAllRead($pdo),
    'moderation_queue' => handleModerationQueue($pdo),
    'moderation_dismiss' => handleModerationDismiss($pdo),
    'moderation_remove' => handleModerationRemove($pdo),
    'settings_get' => handleSettingsGet($pdo),
    'settings_update' => handleSettingsUpdate($pdo),
    default    => jsonResponse(false, 'Invalid action.'),
};


// HELPERS

function jsonResponse(bool $success, string $message, array $data = []): void {
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

function sanitize(string $value): string {
    return htmlspecialchars(strip_tags(trim($value)));
}

// REGISTER

function handleRegister(PDO $pdo): void {
    // NOTE: names below match the "name" attributes in register.php's <form>
    // (full_name, student_number, email, password) — keep these in sync.
    $full_name      = sanitize($_POST['full_name'] ?? '');
    $student_number = sanitize($_POST['student_number'] ?? '');
    $email          = sanitize($_POST['email'] ?? '');
    $password       = $_POST['password'] ?? '';
    $popiaAgree     = $_POST['popiaAgree'] ?? '';

    if ($full_name === '' || $student_number === '' || $email === '' || $password === '') {
        jsonResponse(false, 'Make sure you do not leave any field empty.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, 'Invalid email address.');
    }

    $expected_email = $student_number . '@richfield.ac.za';
    if (strtolower($email) !== strtolower($expected_email)) {
        jsonResponse(false, 'Email must match your student number (' . $expected_email . ').');
    }

    if (strlen($password) < 8) {
        jsonResponse(false, 'Password must be at least 8 characters long.');
    }

    if ($popiaAgree === '') {
        jsonResponse(false, 'You must agree to the POPIA Privacy Policy to register.');
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'An account with this email already exists.');
    }

    // Check if student number already exists
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE student_number = ?');
    $stmt->execute([$student_number]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'This student number is already registered.');
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Every public registration is hardcoded to the student role.
    // Counsellor and admin accounts are created separately by an admin — never through this endpoint.
    $stmt = $pdo->prepare('
        INSERT INTO users (full_name, student_number, email, password_hash, role)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([$full_name, $student_number, $email, $password_hash, 'student']);

    jsonResponse(true, 'Registration successful. You can now log in.');
}

// LOGIN


function handleLogin(PDO $pdo): void {
    // NOTE: names below match the "name" attributes in login.php's <form>
    // (email, password) — keep these in sync.
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        jsonResponse(false, 'Please enter both email and password.');
    }

    $stmt = $pdo->prepare('SELECT user_id, full_name, email, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Deliberately vague on failure — do not reveal whether the email
    // exists or the password was wrong, so this endpoint can't be used
    // to enumerate registered accounts.
    if (!$user || !password_verify($password, $user['password_hash'])) {
        jsonResponse(false, 'Invalid email or password.');
    }

    // Regenerate the session ID on login to prevent session fixation.
    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['user_id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['email']     = $user['email'];
    $_SESSION['role']      = $user['role'];

    jsonResponse(true, 'Login successful.', ['role' => $user['role']]);
}

 
// CHECK-IN
 
function handleCheckin(PDO $pdo): void {
    // Must be logged in — session set by handleLogin()
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in to check in.');
    }
 
    $user_id      = $_SESSION['user_id'];
    $mood         = $_POST['mood'] ?? '';
    $stress_level = $_POST['stress_level'] ?? null;
 
    $allowed_moods = ['great', 'good', 'okay', 'low', 'struggling'];
    if (!in_array($mood, $allowed_moods, true)) {
        jsonResponse(false, 'Invalid mood value.');
    }
 
    if (!is_numeric($stress_level) || $stress_level < 0 || $stress_level > 10) {
        jsonResponse(false, 'Stress level must be a number between 0 and 10.');
    }
    $stress_level = (int) $stress_level;
 
    $checkin_date = date('Y-m-d'); // server date — one check-in per calendar day
 
    // Insert, or overwrite today's entry if the student already checked in
    $stmt = $pdo->prepare('
        INSERT INTO mood_checkins (user_id, mood, stress_level, checkin_date)
        VALUES (:user_id, :mood, :stress_level, :checkin_date)
        ON DUPLICATE KEY UPDATE
            mood = VALUES(mood),
            stress_level = VALUES(stress_level)
    ');
    $stmt->execute([
        ':user_id'      => $user_id,
        ':mood'         => $mood,
        ':stress_level' => $stress_level,
        ':checkin_date' => $checkin_date,
    ]);
 
    jsonResponse(true, 'Check-in saved.', [
        'mood'         => $mood,
        'stress_level' => $stress_level,
        'checkin_date' => $checkin_date,
    ]);
}
 
//Mood History
  
function handleMoodHistory(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
 
    $month = isset($_GET['month']) ? (int) $_GET['month'] : (int) date('n');
    $year  = isset($_GET['year'])  ? (int) $_GET['year']  : (int) date('Y');
 
    if ($month < 1 || $month > 12) {
        jsonResponse(false, 'Invalid month.');
    }
 
    // Calendar view: every check-in in the requested month (for the emoji grid)
    $stmt = $pdo->prepare('
        SELECT DAY(checkin_date) AS day, mood
        FROM mood_checkins
        WHERE user_id = :user_id
          AND YEAR(checkin_date) = :year
          AND MONTH(checkin_date) = :month
        ORDER BY checkin_date ASC
    ');
    $stmt->execute([':user_id' => $user_id, ':year' => $year, ':month' => $month]);
    $calendar = $stmt->fetchAll();
 
    // Last 7 days: trend line (needs a numeric mood_score) + stress bars
    $stmt = $pdo->prepare("
        SELECT
            checkin_date,
            mood,
            stress_level,
            CASE mood
                WHEN 'struggling' THEN 1
                WHEN 'low'        THEN 2
                WHEN 'okay'       THEN 3
                WHEN 'good'       THEN 4
                WHEN 'great'      THEN 5
            END AS mood_score
        FROM mood_checkins
        WHERE user_id = :user_id
          AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        ORDER BY checkin_date ASC
    ");
    $stmt->execute([':user_id' => $user_id]);
    $week = $stmt->fetchAll();
 
    jsonResponse(true, 'Mood history retrieved.', [
        'calendar' => $calendar,  // [{day: 4, mood: 'good'}, ...]
        'week'     => $week,      // [{checkin_date, mood, stress_level, mood_score}, ...]
    ]);
}
 
// WEEKLY MOOD SUMMARY

/*
 * ASSUMPTION FLAGGED FOR THE TEAM:
 * The dashboard mockup's legend has 4 buckets (Fine/Positive/Neutral/Low with
 * CSS classes calm/neutral/low/risk) but mood_checkins has 5 mood values.
 * Mapped as: calm=great, neutral=good, low=okay, risk=low+struggling (merged).
 * This is a clinical-ish categorization choice, not just a coding one —
 * worth confirming with your supervisor/team rather than treating as final.
 */
function handleWeeklyMoodSummary(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('
        SELECT mood, COUNT(*) AS cnt
        FROM mood_checkins
        WHERE user_id = :user_id
          AND checkin_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY mood
    ');
    $stmt->execute([':user_id' => $user_id]);
    $rows = $stmt->fetchAll();

    $counts = ['great' => 0, 'good' => 0, 'okay' => 0, 'low' => 0, 'struggling' => 0];
    foreach ($rows as $row) {
        $counts[$row['mood']] = (int) $row['cnt'];
    }

    $total = array_sum($counts);

    $buckets = [
        'calm'    => $counts['great'],
        'neutral' => $counts['good'],
        'low'     => $counts['okay'],
        'risk'    => $counts['low'] + $counts['struggling'],
    ];

    $percentages = [];
    foreach ($buckets as $key => $count) {
        $percentages[$key] = $total > 0 ? round(($count / $total) * 100) : 0;
    }

    jsonResponse(true, 'Weekly summary retrieved.', [
        'total_checkins' => $total,
        'percentages'     => $percentages,
    ]);
}

// JOURNAL

function handleJournalCreate(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $raw_content = $_POST['content'] ?? '';
    $emotion = trim($_POST['emotion'] ?? '');

    // Byte-size guard BEFORE parsing — a large pasted image should fail fast
    // rather than spend CPU running it through DOMDocument first.
    if (strlen($raw_content) > 4 * 1024 * 1024) {
        jsonResponse(false, 'Journal entry is too large (max ~4MB, including any photo).');
    }

    $content = sanitizeJournalHtml($raw_content);
    $plainCheck = trim(strip_tags($content));
    $hasImage = stripos($content, '<img') !== false;

    if ($plainCheck === '' && !$hasImage) {
        jsonResponse(false, 'Journal entry cannot be empty.');
    }

    $allowed_emotions = ['Grateful', 'Calm', 'Hopeful', 'Anxious', 'Frustrated'];
    if ($emotion !== '' && !in_array($emotion, $allowed_emotions, true)) {
        jsonResponse(false, 'Invalid emotion tag.');
    }
    $emotion_value = $emotion === '' ? null : $emotion;

    $stmt = $pdo->prepare('
        INSERT INTO journal_entries (user_id, content, emotion)
        VALUES (:user_id, :content, :emotion)
    ');
    $stmt->execute([
        ':user_id' => $user_id,
        ':content' => $content,
        ':emotion' => $emotion_value,
    ]);

    jsonResponse(true, 'Journal entry saved.', [
        'entry_id'   => $pdo->lastInsertId(),
        'content'    => $content,
        'emotion'    => $emotion_value,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}

/*
 * Allowlist HTML sanitizer for journal entries.
 *
 * The editor is a contenteditable div, so incoming content is real HTML,
 * not plain text — arbitrary markup can arrive via paste, not just via the
 * toolbar. This walks the DOM and:
 *   - drops <script>/<style> entirely (tag + contents)
 *   - unwraps any other disallowed tag (keeps the text/children, drops the tag)
 *   - strips ALL attributes except a hand-checked href on <a> (http/https only)
 *     and a hand-checked src on <img> (must be a base64 image data: URI —
 *     no remote image URLs, so nothing here ever makes an outbound request)
 * This is intentionally conservative — it never trusts an attribute value
 * just because the tag is allowed.
 */
function sanitizeJournalHtml(string $html): string {
    $html = trim($html);
    if ($html === '') {
        return '';
    }

    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML(
        '<?xml encoding="utf-8" ?><div id="__root">' . $html . '</div>',
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );
    libxml_clear_errors();

    $root = $doc->getElementById('__root');
    if (!$root) {
        return '';
    }

    $allowedTags = ['b', 'strong', 'i', 'em', 'u', 'ul', 'ol', 'li', 'br', 'p', 'div', 'span', 'a', 'img'];
    sanitizeJournalNode($root, $allowedTags);

    $result = '';
    foreach ($root->childNodes as $child) {
        $result .= $doc->saveHTML($child);
    }
    return $result;
}

function sanitizeJournalNode(DOMNode $node, array $allowedTags): void {
    // Snapshot child list first — we mutate the tree (unwrap/remove) while iterating.
    $children = iterator_to_array($node->childNodes);

    foreach ($children as $child) {
        if ($child->nodeType !== XML_ELEMENT_NODE) {
            continue;
        }
        $tag = strtolower($child->nodeName);

        if ($tag === 'script' || $tag === 'style') {
            $child->parentNode->removeChild($child);
            continue;
        }

        if (!in_array($tag, $allowedTags, true)) {
            while ($child->firstChild) {
                $child->parentNode->insertBefore($child->firstChild, $child);
            }
            $child->parentNode->removeChild($child);
            continue;
        }

        $href = $tag === 'a' ? $child->getAttribute('href') : null;
        $src  = $tag === 'img' ? $child->getAttribute('src') : null;

        foreach (iterator_to_array($child->attributes ?? []) as $attr) {
            $child->removeAttribute($attr->name);
        }

        if ($tag === 'a') {
            if ($href !== null && preg_match('#^https?://#i', $href)) {
                $child->setAttribute('href', $href);
                $child->setAttribute('target', '_blank');
                $child->setAttribute('rel', 'noopener noreferrer');
            } else {
                // No valid http(s) href — unwrap rather than leave a dead/unsafe link
                while ($child->firstChild) {
                    $child->parentNode->insertBefore($child->firstChild, $child);
                }
                $child->parentNode->removeChild($child);
                continue;
            }
        }

        if ($tag === 'img') {
            $validImage = $src !== null
                && preg_match('#^data:image/(png|jpe?g|gif|webp);base64,[A-Za-z0-9+/=]+$#', $src)
                && strlen($src) <= 3 * 1024 * 1024; // ~3MB per image after base64 overhead
            if ($validImage) {
                $child->setAttribute('src', $src);
                $child->setAttribute('style', 'max-width:100%; border-radius:8px; margin:8px 0; display:block;');
            } else {
                $child->parentNode->removeChild($child);
                continue;
            }
        }

        sanitizeJournalNode($child, $allowedTags);
    }
}

function handleJournalList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    // Bounded, server-controlled int — safe to interpolate directly since
    // LIMIT placeholders are unreliable under PDO's emulated prepares.
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
    $limit = max(1, min($limit, 50));

    $stmt = $pdo->prepare("
        SELECT entry_id, content, emotion, created_at
        FROM journal_entries
        WHERE user_id = :user_id
        ORDER BY created_at DESC
        LIMIT {$limit}
    ");
    $stmt->execute([':user_id' => $user_id]);
    $entries = $stmt->fetchAll();

    jsonResponse(true, 'Journal entries retrieved.', ['entries' => $entries]);
}

// SELF-HELP LIBRARY (read-only for students — curated by counsellors/admins)

function handleResourceList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }

    $category = trim($_GET['category'] ?? '');
    $allowed_categories = ['Anxiety', 'Academic Stress', 'Sleep', 'Relationships', 'Self Confidence'];

    if ($category !== '' && !in_array($category, $allowed_categories, true)) {
        jsonResponse(false, 'Invalid category.');
    }

    // Crisis Support resources are always included, regardless of the
    // selected category — a crisis contact should never be filterable out.
    if ($category === '') {
        $stmt = $pdo->prepare('SELECT resource_id, title, subtitle, category, resource_type, icon, url FROM resources ORDER BY category, resource_id');
        $stmt->execute();
    } else {
        $stmt = $pdo->prepare("
            SELECT resource_id, title, subtitle, category, resource_type, icon, url
            FROM resources
            WHERE category = :category OR category = 'Crisis Support'
            ORDER BY (category = 'Crisis Support'), resource_id
        ");
        $stmt->execute([':category' => $category]);
    }

    jsonResponse(true, 'Resources retrieved.', ['resources' => $stmt->fetchAll()]);
}

// COUNSELLING BOOKING

// Fixed weekday slot template — no per-counsellor availability management
// exists yet, so every counsellor offers the same 4 daily slots, weekdays only.
const BOOKING_SLOT_TEMPLATE = ['09:00:00', '11:30:00', '14:00:00', '15:30:00'];

function handleCounsellorList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $stmt = $pdo->query('SELECT counsellor_id, name, specialties, avatar FROM counsellors ORDER BY name');
    jsonResponse(true, 'Counsellors retrieved.', ['counsellors' => $stmt->fetchAll()]);
}

function handleSlotList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }

    $counsellor_id = (int) ($_GET['counsellor_id'] ?? 0);
    $date = $_GET['date'] ?? '';

    if ($counsellor_id <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        jsonResponse(false, 'Counsellor and date are required.');
    }

    $dt = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dt || $dt->format('Y-m-d') !== $date) {
        jsonResponse(false, 'Invalid date.');
    }

    $today = new DateTime('today');
    if ($dt < $today) {
        jsonResponse(true, 'No slots — date is in the past.', ['slots' => []]);
    }

    $weekday = (int) $dt->format('N'); // 1=Mon .. 7=Sun
    if ($weekday >= 6) {
        jsonResponse(true, 'No slots — counsellors are only available on weekdays.', ['slots' => []]);
    }

    $stmt = $pdo->prepare('
        SELECT session_time FROM bookings
        WHERE counsellor_id = :counsellor_id AND session_date = :date AND status = "booked"
    ');
    $stmt->execute([':counsellor_id' => $counsellor_id, ':date' => $date]);
    $taken = array_column($stmt->fetchAll(), 'session_time');

    $slots = [];
    foreach (BOOKING_SLOT_TEMPLATE as $time) {
        $slots[] = ['time' => substr($time, 0, 5), 'available' => !in_array($time, $taken, true)];
    }

    jsonResponse(true, 'Slots retrieved.', ['slots' => $slots]);
}

function handleBookingCreate(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $counsellor_id = (int) ($_POST['counsellor_id'] ?? 0);
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    if ($counsellor_id <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        jsonResponse(false, 'Counsellor and date are required.');
    }
    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        jsonResponse(false, 'Invalid time slot.');
    }
    $time_full = $time . ':00';
    if (!in_array($time_full, BOOKING_SLOT_TEMPLATE, true)) {
        jsonResponse(false, 'That time slot does not exist.');
    }

    $dt = DateTime::createFromFormat('Y-m-d', $date);
    if (!$dt || $dt->format('Y-m-d') !== $date || $dt < new DateTime('today')) {
        jsonResponse(false, 'Invalid or past date.');
    }
    if ((int) $dt->format('N') >= 6) {
        jsonResponse(false, 'Counsellors are only available on weekdays.');
    }

    // Enforce: one active upcoming appointment per student at a time —
    // matches the UI, which only ever shows a single "Upcoming Appointment" card.
    $stmt = $pdo->prepare('
        SELECT booking_id FROM bookings
        WHERE user_id = :user_id AND status = "booked" AND session_date >= CURDATE()
    ');
    $stmt->execute([':user_id' => $user_id]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'You already have an upcoming appointment. Cancel or reschedule it first.');
    }

    // Re-check the slot is still free right before inserting (best-effort —
    // a true race condition would need a DB-level lock, out of scope here).
    $stmt = $pdo->prepare('
        SELECT booking_id FROM bookings
        WHERE counsellor_id = :counsellor_id AND session_date = :date AND session_time = :time AND status = "booked"
    ');
    $stmt->execute([':counsellor_id' => $counsellor_id, ':date' => $date, ':time' => $time_full]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'That slot was just booked by someone else. Please pick another.');
    }

    $stmt = $pdo->prepare('
        INSERT INTO bookings (user_id, counsellor_id, session_date, session_time)
        VALUES (:user_id, :counsellor_id, :date, :time)
    ');
    $stmt->execute([
        ':user_id' => $user_id,
        ':counsellor_id' => $counsellor_id,
        ':date' => $date,
        ':time' => $time_full,
    ]);

    $stmt = $pdo->prepare('SELECT name FROM counsellors WHERE counsellor_id = :id');
    $stmt->execute([':id' => $counsellor_id]);
    $counsellorName = $stmt->fetch()['name'] ?? 'your counsellor';
    $displayDate = $dt->format('l, j F Y');
    if (userWantsBookingReminders($pdo, $user_id)) {
        createNotification(
            $pdo, $user_id, 'booking_created',
            'Counselling session booked',
            "Your session with {$counsellorName} is on {$displayDate} at {$time}."
        );
    }

    jsonResponse(true, 'Session booked.', ['booking_id' => $pdo->lastInsertId()]);
}

function handleBookingCurrent(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('
        SELECT b.booking_id, b.session_date, b.session_time, c.name AS counsellor_name, c.avatar
        FROM bookings b
        JOIN counsellors c ON c.counsellor_id = b.counsellor_id
        WHERE b.user_id = :user_id AND b.status = "booked" AND b.session_date >= CURDATE()
        ORDER BY b.session_date, b.session_time
        LIMIT 1
    ');
    $stmt->execute([':user_id' => $user_id]);
    $booking = $stmt->fetch();

    jsonResponse(true, 'Current booking retrieved.', ['booking' => $booking ?: null]);
}

function handleBookingCancel(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
    $booking_id = (int) ($_POST['booking_id'] ?? 0);

    if ($booking_id <= 0) {
        jsonResponse(false, 'Invalid booking.');
    }

    // Fetch details before cancelling so the notification can name the slot.
    $stmt = $pdo->prepare('SELECT session_date, session_time FROM bookings WHERE booking_id = :id AND user_id = :user_id');
    $stmt->execute([':id' => $booking_id, ':user_id' => $user_id]);
    $bookingDetails = $stmt->fetch();

    // WHERE includes user_id so a student can never cancel someone else's booking.
    $stmt = $pdo->prepare('
        UPDATE bookings SET status = "cancelled"
        WHERE booking_id = :booking_id AND user_id = :user_id AND status = "booked"
    ');
    $stmt->execute([':booking_id' => $booking_id, ':user_id' => $user_id]);

    if ($stmt->rowCount() === 0) {
        jsonResponse(false, 'Booking not found, not yours, or already cancelled.');
    }

    if ($bookingDetails && userWantsBookingReminders($pdo, $user_id)) {
        $displayDate = (new DateTime($bookingDetails['session_date']))->format('l, j F Y');
        $displayTime = substr($bookingDetails['session_time'], 0, 5);
        createNotification(
            $pdo, $user_id, 'booking_cancelled',
            'Counselling session cancelled',
            "Your session on {$displayDate} at {$displayTime} was cancelled."
        );
    }

    jsonResponse(true, 'Appointment cancelled.');
}

// PEER SUPPORT BOARD
//
// Posts are pseudonymous, not anonymous in the database — every post,
// reply, and report is tied to user_id so staff could follow up if needed.
// Only the DISPLAY hides identity (frontend always shows "Anonymous
// Student"). See peer_support.sql for the moderation-gap note: reporting a
// post currently just saves a row — there is no admin/counsellor screen
// that reads post_reports yet.

function handlePostCreate(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $content = trim(strip_tags($_POST['content'] ?? ''));
    $category = trim($_POST['category'] ?? 'Support');
    $allowed_categories = ['Support', 'Vent', 'Advice', 'Encouragement'];

    if ($content === '') {
        jsonResponse(false, 'Write something before posting.');
    }
    if (mb_strlen($content) > 2000) {
        jsonResponse(false, 'Post is too long (max 2000 characters).');
    }
    if (!in_array($category, $allowed_categories, true)) {
        $category = 'Support';
    }

    $stmt = $pdo->prepare('INSERT INTO posts (user_id, content, category) VALUES (:user_id, :content, :category)');
    $stmt->execute([':user_id' => $user_id, ':content' => $content, ':category' => $category]);

    jsonResponse(true, 'Posted.', [
        'post_id' => $pdo->lastInsertId(),
        'content' => $content,
        'category' => $category,
        'created_at' => date('Y-m-d H:i:s'),
        'like_count' => 0,
        'reply_count' => 0,
        'liked_by_me' => false,
        'is_mine' => true,
    ]);
}

function handlePostList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
    $tab = $_GET['tab'] ?? 'recent';

    $where = 'WHERE p.is_removed = 0';
    $order = 'p.created_at DESC';
    if ($tab === 'mine') {
        $where .= ' AND p.user_id = :me';
    } elseif ($tab === 'popular') {
        $order = 'like_count DESC, p.created_at DESC';
    }

    $stmt = $pdo->prepare("
        SELECT
            p.post_id, p.content, p.category, p.created_at,
            (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.post_id) AS like_count,
            (SELECT COUNT(*) FROM post_replies pr WHERE pr.post_id = p.post_id) AS reply_count,
            EXISTS(SELECT 1 FROM post_likes pl2 WHERE pl2.post_id = p.post_id AND pl2.user_id = :me) AS liked_by_me,
            (p.user_id = :me) AS is_mine
        FROM posts p
        {$where}
        ORDER BY {$order}
        LIMIT 50
    ");
    $stmt->execute([':me' => $user_id]);
    $posts = $stmt->fetchAll();

    // Cast MySQL's 0/1 to real booleans for a cleaner frontend contract
    foreach ($posts as &$p) {
        $p['liked_by_me'] = (bool) $p['liked_by_me'];
        $p['is_mine'] = (bool) $p['is_mine'];
        $p['like_count'] = (int) $p['like_count'];
        $p['reply_count'] = (int) $p['reply_count'];
    }

    jsonResponse(true, 'Posts retrieved.', ['posts' => $posts]);
}

function handlePostLike(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
    $post_id = (int) ($_POST['post_id'] ?? 0);
    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }

    $stmt = $pdo->prepare('SELECT like_id FROM post_likes WHERE post_id = :post_id AND user_id = :user_id');
    $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $stmt = $pdo->prepare('DELETE FROM post_likes WHERE like_id = :like_id');
        $stmt->execute([':like_id' => $existing['like_id']]);
        $liked = false;
    } else {
        $stmt = $pdo->prepare('INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)');
        $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
        $liked = true;
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM post_likes WHERE post_id = :post_id');
    $stmt->execute([':post_id' => $post_id]);
    $count = (int) $stmt->fetch()['cnt'];

    jsonResponse(true, $liked ? 'Liked.' : 'Unliked.', ['liked_by_me' => $liked, 'like_count' => $count]);
}

function handlePostReplyCreate(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
    $post_id = (int) ($_POST['post_id'] ?? 0);
    $content = trim(strip_tags($_POST['content'] ?? ''));

    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }
    if ($content === '') {
        jsonResponse(false, 'Write a reply before submitting.');
    }
    if (mb_strlen($content) > 1000) {
        jsonResponse(false, 'Reply is too long (max 1000 characters).');
    }

    $stmt = $pdo->prepare('SELECT post_id FROM posts WHERE post_id = :post_id');
    $stmt->execute([':post_id' => $post_id]);
    if (!$stmt->fetch()) {
        jsonResponse(false, 'That post no longer exists.');
    }

    $stmt = $pdo->prepare('INSERT INTO post_replies (post_id, user_id, content) VALUES (:post_id, :user_id, :content)');
    $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id, ':content' => $content]);

    jsonResponse(true, 'Reply posted.', [
        'reply_id' => $pdo->lastInsertId(),
        'content' => $content,
        'created_at' => date('Y-m-d H:i:s'),
    ]);
}

function handlePostReplyList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $post_id = (int) ($_GET['post_id'] ?? 0);
    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }

    $stmt = $pdo->prepare('SELECT reply_id, content, created_at FROM post_replies WHERE post_id = :post_id ORDER BY created_at ASC');
    $stmt->execute([':post_id' => $post_id]);

    jsonResponse(true, 'Replies retrieved.', ['replies' => $stmt->fetchAll()]);
}

function handlePostReport(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];
    $post_id = (int) ($_POST['post_id'] ?? 0);
    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }

    $stmt = $pdo->prepare('SELECT report_id FROM post_reports WHERE post_id = :post_id AND user_id = :user_id');
    $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'You already reported this post.');
    }

    $stmt = $pdo->prepare('INSERT INTO post_reports (post_id, user_id) VALUES (:post_id, :user_id)');
    $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);

    jsonResponse(true, 'Post reported. Thank you for helping keep this space safe.');
}

// ACHIEVEMENTS
//
// XP weights below are MY OWN invented default (+10 check-in, +15 journal
// entry, +5 peer post, +5 peer reply, +20 counselling booking; 1000 XP per
// level) — nothing in the spec defined a scoring system, so this is a
// placeholder game-design choice, not something to treat as agreed/final.
//
// "Resources Used" has no real number behind it: nothing tracks resource
// views/clicks anywhere in the project. Rather than fabricate a count, the
// stat is returned as null and the frontend shows it as untracked.

function longestCheckinStreak(array $sortedDates): int {
    if (empty($sortedDates)) return 0;
    $longest = 1;
    $current = 1;
    for ($i = 1; $i < count($sortedDates); $i++) {
        $prev = new DateTime($sortedDates[$i - 1]);
        $cur = new DateTime($sortedDates[$i]);
        $diff = (int) $prev->diff($cur)->days;
        $current = ($diff === 1) ? $current + 1 : 1;
        $longest = max($longest, $current);
    }
    return $longest;
}

function handleAchievementsSummary(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('SELECT checkin_date FROM mood_checkins WHERE user_id = :id ORDER BY checkin_date ASC');
    $stmt->execute([':id' => $user_id]);
    $checkinDates = array_column($stmt->fetchAll(), 'checkin_date');
    $checkinCount = count($checkinDates);

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM journal_entries WHERE user_id = :id');
    $stmt->execute([':id' => $user_id]);
    $journalCount = (int) $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM posts WHERE user_id = :id');
    $stmt->execute([':id' => $user_id]);
    $postCount = (int) $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM post_replies WHERE user_id = :id');
    $stmt->execute([':id' => $user_id]);
    $replyCount = (int) $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM bookings WHERE user_id = :id AND status IN ('booked','completed')");
    $stmt->execute([':id' => $user_id]);
    $bookingCount = (int) $stmt->fetch()['cnt'];

    $stmt = $pdo->prepare('
        SELECT d FROM (
            SELECT checkin_date AS d FROM mood_checkins WHERE user_id = :id
            UNION
            SELECT DATE(created_at) AS d FROM journal_entries WHERE user_id = :id
            UNION
            SELECT DATE(created_at) AS d FROM posts WHERE user_id = :id
            UNION
            SELECT DATE(created_at) AS d FROM post_replies WHERE user_id = :id
        ) AS activity
    ');
    $stmt->execute([':id' => $user_id]);
    $daysActive = count($stmt->fetchAll());

    $xpPerLevel = 1000;
    $xp = ($checkinCount * 10) + ($journalCount * 15) + ($postCount * 5) + ($replyCount * 5) + ($bookingCount * 20);
    $level = intdiv($xp, $xpPerLevel) + 1;
    $xpIntoLevel = $xp % $xpPerLevel;

    $streak = longestCheckinStreak($checkinDates);
    $featuresUsedCount = (int) ($checkinCount > 0) + (int) ($journalCount > 0) + (int) ($bookingCount > 0) + (int) (($postCount + $replyCount) > 0);

    $badges = [
        ['key' => 'streak', 'name' => '7 Day Check-in Streak', 'icon' => '🔥', 'unlocked' => $streak >= 7],
        ['key' => 'first_journal', 'name' => 'First Journal Entry', 'icon' => '🛡', 'unlocked' => $journalCount >= 1],
        ['key' => 'explorer', 'name' => 'Wellness Explorer', 'icon' => '🏆', 'unlocked' => $featuresUsedCount >= 3],
        ['key' => 'peer_helper', 'name' => 'Peer Helper', 'icon' => '☘', 'unlocked' => $replyCount >= 1],
    ];

    jsonResponse(true, 'Achievements retrieved.', [
        'xp' => $xp,
        'level' => $level,
        'xp_into_level' => $xpIntoLevel,
        'xp_per_level' => $xpPerLevel,
        'stats' => [
            'checkins' => $checkinCount,
            'journal_entries' => $journalCount,
            'resources_used' => null, // not tracked anywhere — see note above
            'days_active' => $daysActive,
        ],
        'badges' => $badges,
    ]);
}

// NOTIFICATIONS
//
// Only booking_created / booking_cancelled are real, stored notifications
// (triggered directly from handleBookingCreate/handleBookingCancel). A
// weekly "mood report ready" notice and a "new resource" notice both
// existed in the original mockup but aren't generated here — see
// notifications.sql for why (no cron/scheduler, no admin resource-add
// trigger exists in this project).

function createNotification(PDO $pdo, int $user_id, string $type, string $title, string $message): void {
    $stmt = $pdo->prepare('
        INSERT INTO notifications (user_id, type, title, message)
        VALUES (:user_id, :type, :title, :message)
    ');
    $stmt->execute([
        ':user_id' => $user_id,
        ':type' => $type,
        ':title' => $title,
        ':message' => $message,
    ]);
}

function handleNotificationList(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('
        SELECT notification_id, type, title, message, is_read, created_at
        FROM notifications
        WHERE user_id = :user_id
        ORDER BY created_at DESC
        LIMIT 30
    ');
    $stmt->execute([':user_id' => $user_id]);
    $notifications = $stmt->fetchAll();
    foreach ($notifications as &$n) {
        $n['is_read'] = (bool) $n['is_read'];
    }

    jsonResponse(true, 'Notifications retrieved.', ['notifications' => $notifications]);
}

function handleNotificationMarkAllRead(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0');
    $stmt->execute([':user_id' => $user_id]);

    jsonResponse(true, 'All notifications marked as read.');
}

// PEER SUPPORT MODERATION
//
// *** TEMPORARY / INSECURE — FLAGGED DELIBERATELY, NOT AN OVERSIGHT ***
// There is no counsellor/admin login system in this project yet — see the
// note in counselling-booking's counsellors table and the earlier
// peer_support.sql comment. These three endpoints only check that SOME
// student is logged in — there is NO role check, because there is
// currently no role to check that anyone can actually log in as. That
// means, right now, ANY logged-in student can view reported posts (which
// reveal who reported what) and can remove any other student's post. This
// must be locked down behind real counsellor authentication before this
// screen is used for anything beyond local development/demo purposes.

function handleModerationQueue(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }

    $stmt = $pdo->query('
        SELECT
            p.post_id, p.content, p.category, p.created_at, p.is_removed,
            (SELECT COUNT(*) FROM post_reports pr WHERE pr.post_id = p.post_id AND pr.reviewed = 0) AS pending_report_count
        FROM posts p
        WHERE p.post_id IN (SELECT post_id FROM post_reports WHERE reviewed = 0)
        ORDER BY pending_report_count DESC, p.created_at DESC
    ');
    $posts = $stmt->fetchAll();
    foreach ($posts as &$p) {
        $p['is_removed'] = (bool) $p['is_removed'];
        $p['pending_report_count'] = (int) $p['pending_report_count'];
    }

    jsonResponse(true, 'Moderation queue retrieved.', ['posts' => $posts]);
}

function handleModerationDismiss(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $post_id = (int) ($_POST['post_id'] ?? 0);
    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }

    $stmt = $pdo->prepare('UPDATE post_reports SET reviewed = 1 WHERE post_id = :post_id');
    $stmt->execute([':post_id' => $post_id]);

    jsonResponse(true, 'Reports dismissed — post stays up.');
}

function handleModerationRemove(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $post_id = (int) ($_POST['post_id'] ?? 0);
    if ($post_id <= 0) {
        jsonResponse(false, 'Invalid post.');
    }

    $stmt = $pdo->prepare('UPDATE posts SET is_removed = 1 WHERE post_id = :post_id');
    $stmt->execute([':post_id' => $post_id]);

    $stmt = $pdo->prepare('UPDATE post_reports SET reviewed = 1 WHERE post_id = :post_id');
    $stmt->execute([':post_id' => $post_id]);

    jsonResponse(true, 'Post removed from the board.');
}

// SETTINGS

function handleSettingsGet(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare('SELECT dark_mode, booking_reminders, weekly_mood_email FROM user_settings WHERE user_id = :id');
    $stmt->execute([':id' => $user_id]);
    $row = $stmt->fetch();

    // No row yet — a student who never touched settings gets sensible
    // defaults rather than an error.
    $settings = $row ?: ['dark_mode' => 0, 'booking_reminders' => 1, 'weekly_mood_email' => 1];
    $settings['dark_mode'] = (bool) $settings['dark_mode'];
    $settings['booking_reminders'] = (bool) $settings['booking_reminders'];
    $settings['weekly_mood_email'] = (bool) $settings['weekly_mood_email'];

    jsonResponse(true, 'Settings retrieved.', $settings);
}

function handleSettingsUpdate(PDO $pdo): void {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        jsonResponse(false, 'You must be logged in.');
    }
    $user_id = $_SESSION['user_id'];

    $dark_mode = ($_POST['dark_mode'] ?? '0') === '1' ? 1 : 0;
    $booking_reminders = ($_POST['booking_reminders'] ?? '1') === '1' ? 1 : 0;
    $weekly_mood_email = ($_POST['weekly_mood_email'] ?? '1') === '1' ? 1 : 0;

    $stmt = $pdo->prepare('
        INSERT INTO user_settings (user_id, dark_mode, booking_reminders, weekly_mood_email)
        VALUES (:user_id, :dark_mode, :booking_reminders, :weekly_mood_email)
        ON DUPLICATE KEY UPDATE
            dark_mode = VALUES(dark_mode),
            booking_reminders = VALUES(booking_reminders),
            weekly_mood_email = VALUES(weekly_mood_email)
    ');
    $stmt->execute([
        ':user_id' => $user_id,
        ':dark_mode' => $dark_mode,
        ':booking_reminders' => $booking_reminders,
        ':weekly_mood_email' => $weekly_mood_email,
    ]);

    jsonResponse(true, 'Settings saved.');
}

function userWantsBookingReminders(PDO $pdo, int $user_id): bool {
    $stmt = $pdo->prepare('SELECT booking_reminders FROM user_settings WHERE user_id = :id');
    $stmt->execute([':id' => $user_id]);
    $row = $stmt->fetch();
    return $row ? (bool) $row['booking_reminders'] : true; // default on if never set
}
