<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/database.php';

try {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
        exit;
    }
    $payload = json_decode(file_get_contents('php://input'), true);
    if (!$payload) {
        echo json_encode(['success' => false, 'message' => 'Invalid payload']);
        exit;
    }

    $db = new database();
    $pdo = $db->opencon();

    $user_id = (int)$_SESSION['user_id'];
    $full_name = trim($payload['full_name'] ?? '');
    $phone = trim($payload['phone'] ?? '');
    $street = trim($payload['street'] ?? '');
    $barangay = trim($payload['barangay'] ?? '');
    $municipality = trim($payload['municipality'] ?? '');
    $province = trim($payload['province'] ?? '');
    $event_date = $payload['event_date'] ?? null; // YYYY-MM-DD

    $package_id = (int)($payload['package_id'] ?? 0);
    $package_name = trim($payload['package_name'] ?? '');
    $package_pax = (int)($payload['package_pax'] ?? 0);
    $base_price = (float)($payload['base_price'] ?? 0);
    $addon_pax = max(0, (int)($payload['addon_pax'] ?? 0));
    $chairs = max(0, (int)($payload['chairs'] ?? 0));
    $tables = max(0, (int)($payload['tables'] ?? 0));
    $notes = trim($payload['notes'] ?? '');

    $total_price = (float)($payload['total_price'] ?? 0);
    $deposit_amount = (float)($payload['deposit_amount'] ?? 0);
    $pay_type = trim($payload['pay_type'] ?? '');
    $pay_number = trim($payload['pay_number'] ?? '');

    if (!$full_name || !$phone || !$street || !$barangay || !$municipality || !$province || !$event_date || !$package_name || $base_price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Build cp_place as "Street , Barangay , Municipality , Province"
    $cp_place = $street . ' , ' . $barangay . ' , ' . $municipality . ' , ' . $province;

    // Calculate total and deposit server-side to prevent tampering
    $calc_total = $base_price + ($addon_pax * 200);
    $calc_deposit = round($calc_total * 0.5, 2);
    if (abs($calc_total - $total_price) > 0.01 || abs($calc_deposit - $deposit_amount) > 0.01) {
        echo json_encode(['success' => false, 'message' => 'Invalid totals submitted']);
        exit;
    }

    $pdo->beginTransaction();

    // 1) Insert into cateringpackages
    $notesCombined = $notes;
    $extras = [];
    if ($chairs > 0) { $extras[] = "Chairs: {$chairs}"; }
    if ($tables > 0) { $extras[] = "Tables: {$tables}"; }
    if (!empty($extras)) {
        $notesCombined = trim(($notesCombined ? ($notesCombined . ' | ') : '') . implode(', ', $extras));
    }
    $stmt = $pdo->prepare("INSERT INTO cateringpackages (user_id, cp_name, cp_phone, cp_place, cp_date, cp_price, cp_addon_pax, cp_notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$user_id, $full_name, $phone, $cp_place, $event_date, $calc_total, $addon_pax, $notesCombined]);
    $cp_id = (int)$pdo->lastInsertId();

    // 2) Create order (optional for alignment with payments table)
    $order_status = 'pending';
    $order_needed = $event_date;
    $order_id = 0;
    try {
        $order_id = $db->addOrder($user_id, date('Y-m-d'), $order_status, $calc_total, $order_needed);
    } catch (Throwable $e) {
        // continue without order if schema differs
        $order_id = null;
    }

    // 3) Insert payment as 50% deposit
    // Normalize to match enum in some schemas (Cash/Online/Credit). We'll store Online for digital payments.
    $pay_method = 'Online';
    $pay_status = 'Pending';
    $pay_date = date('Y-m-d');

    $res = $db->savePayment($order_id, $cp_id, $user_id, $pay_date, $calc_deposit, $pay_method, $pay_status);
    if ($res !== true) {
        throw new Exception(is_string($res) ? $res : 'Payment save failed');
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'cp_id' => $cp_id, 'order_id' => $order_id]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) { $pdo->rollBack(); }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
