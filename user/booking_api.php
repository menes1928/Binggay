<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
header('Content-Type: application/json');

require_once __DIR__ . '/../classes/database.php';
$db = new database();
$pdo = $db->opencon();

// Require login
$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'Please login first.']); exit; }

// Accept POST only
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']); exit; }

function strOrNull($v){ $v = isset($v) ? trim((string)$v) : ''; return $v === '' ? null : $v; }

try {
    $fullName = strOrNull($_POST['fullName'] ?? '');
    $email = strOrNull($_POST['email'] ?? '');
    $phone1 = strOrNull($_POST['phone'] ?? '');
    $phone2 = strOrNull($_POST['altPhone'] ?? '');
    $eventTypeId = isset($_POST['eventTypeId']) ? (int)$_POST['eventTypeId'] : 0;
    $packageId = isset($_POST['packageId']) ? (int)$_POST['packageId'] : 0;
    $venueName = strOrNull($_POST['venueName'] ?? '');
    $venueStreet = strOrNull($_POST['venueStreet'] ?? '');
    $venueBarangay = strOrNull($_POST['venueBarangay'] ?? '');
    $venueCity = strOrNull($_POST['venueCity'] ?? '');
    $venueProvince = strOrNull($_POST['venueProvince'] ?? '');
    $eventDate = strOrNull($_POST['eventDate'] ?? ''); // YYYY-MM-DD
    $eventTime = strOrNull($_POST['eventTime'] ?? ''); // HH:mm
    $notes = strOrNull($_POST['notes'] ?? '');
    $agree = isset($_POST['agree']) && ($_POST['agree'] === '1' || $_POST['agree'] === 'true' || $_POST['agree'] === 'on');
    // Add-ons list as CSV for eb_addon_pax per spec (Pax, Table, Chairs, Utensils, Waiters)
    $addons = isset($_POST['addons']) ? (array)$_POST['addons'] : [];
    $addons = array_values(array_filter(array_map('trim', $addons), fn($s)=>$s!==''));

    if (!$fullName || !$email || !$phone1 || !$eventDate || !$eventTime || !$agree || $eventTypeId<=0 || $packageId<=0) {
        echo json_encode(['success'=>false,'message'=>'Please complete all required fields.']); exit;
    }
    // Phone normalization: 11 digits
    $digits1 = preg_replace('/\D+/', '', $phone1); if (strlen($digits1)!==11) { echo json_encode(['success'=>false,'message'=>'Contact Number must be 11 digits.']); exit; }
    $ebContact = $digits1;
    if ($phone2) { $digits2 = preg_replace('/\D+/', '', $phone2); if ($digits2!=='') { $ebContact .= ', ' . $digits2; } }

    // Venue composed string (Name, Street, Barangay, City/Municipality, Province)
    $venueParts = array_filter([$venueName, $venueStreet, $venueBarangay, $venueCity, $venueProvince], fn($v)=>$v && $v!=='' );
    $ebVenue = implode(' , ', $venueParts);
    if (!$ebVenue) { echo json_encode(['success'=>false,'message'=>'Venue is required.']); exit; }

    // Combine date and time to timestamp; require at least 14 days lead time
    $dtStr = $eventDate . ' ' . $eventTime . ':00';
    $ts = strtotime($dtStr);
    if ($ts === false) { echo json_encode(['success'=>false,'message'=>'Invalid event date/time']); exit; }
    $minTs = strtotime('+14 days');
    if ($ts < $minTs) { echo json_encode(['success'=>false,'message'=>'Event date must be at least 14 days from now.']); exit; }
    $ebDate = date('Y-m-d H:i:s', $ts);

    // Validate event type and allowed package mapping
    $chk = $pdo->prepare('SELECT 1 FROM event_type_packages WHERE event_type_id=? AND package_id=?');
    $chk->execute([$eventTypeId, $packageId]);
    if (!$chk->fetchColumn()) { echo json_encode(['success'=>false,'message'=>'Selected package is not allowed for this event type.']); exit; }

    // Pull package info for eb_order label and optional pax
    $p = $pdo->prepare('SELECT name, pax FROM packages WHERE package_id=?');
    $p->execute([$packageId]);
    $pkg = $p->fetch(PDO::FETCH_ASSOC) ?: [];
    $pkgLabel = ($pkg['name'] ?? 'Package') . (isset($pkg['pax']) && $pkg['pax']!=='' ? (' - ' . $pkg['pax']) : '');

    // eb_addon_pax CSV
    $ebAddon = $addons ? implode(', ', $addons) : null;

    // Persist
    $stmt = $pdo->prepare('INSERT INTO eventbookings (user_id, event_type_id, package_id, eb_name, eb_contact, eb_venue, eb_date, eb_order, eb_status, eb_addon_pax, eb_notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $ok = $stmt->execute([
        (int)$userId,
        (int)$eventTypeId,
        (int)$packageId,
        $fullName,
        $ebContact,
        $ebVenue,
        $ebDate,
        $pkgLabel,
        'Pending',
        $ebAddon,
        $notes
    ]);

    if ($ok) {
        echo json_encode(['success'=>true, 'message'=>'Booking submitted successfully']);
    } else {
        echo json_encode(['success'=>false, 'message'=>'Failed to save booking']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Server error']);
}
// end of file
