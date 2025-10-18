<?php
// Central gate for /admin access
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
$uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if ($uid <= 0) {
    header('Location: ../home');
    exit;
}
header('Location: ../user/home');
exit;
