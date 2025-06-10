<?php
// config.php

// Display errors for debugging (comment out in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session, essential for login/admin pages
session_start();

// Include your database connection parameters
require_once 'db.php'; 

// Security function for data display
function h($str) {
    if ($str === null) return '';
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>