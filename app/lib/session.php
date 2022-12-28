<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['previousPage'])) {
    if (basename($_SERVER['REQUEST_URI']) !== $_SESSION['previousPage']['requestUri'] && new DateTime() !== $_SESSION['previousPage']['dateTime']) {
        unset($_SESSION['toast']);
    }
}