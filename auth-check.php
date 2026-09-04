<?php
/*
 * auth-check.php — include this as the VERY FIRST LINE of any protected
 * student page, before any HTML output:
 *
 *   <?php require_once __DIR__ . '/auth-check.php'; ?>
 *   <!DOCTYPE html>
 *   ...
 *
 * Redirects to login.php if there's no valid student session.
 */

session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'student') {
    header('Location: login.php');
    exit;
}
