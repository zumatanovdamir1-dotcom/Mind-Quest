<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$page = $_GET['page'] ?? 'home';

$allowedPages = ['home', 'quests', 'booking', 'profile', 'admin', 'login', 'register', 'logout', 'payment_demo'];
if (!in_array($page, $allowedPages)) {
    $page = 'home';
}

if ($page === 'logout') {
    session_destroy();
    echo "<script>window.location.href='index.php?page=home';</script>";
    exit;
}

include 'templates/header.php';

switch ($page) {
    case 'home': include 'pages/home.php'; break;
    case 'quests': include 'pages/quests.php'; break;
    case 'booking': 
        if (isset($_GET['id'])) {
            include 'pages/booking.php';
        } else {
            echo "<script>window.location.href='index.php?page=quests';</script>";
        }
        break;
    case 'payment_demo':
        if (isset($_GET['id'])) {
            include 'pages/payment_demo.php';
        } else {
            echo "<script>window.location.href='index.php?page=profile';</script>";
        }
        break;
    case 'profile': 
        if (!isLoggedIn()) { 
            echo "<script>window.location.href='index.php?page=login';</script>";
            exit;
        }
        include 'pages/profile.php'; 
        break;
    case 'admin':
        if (!isAdmin()) { 
            echo "<script>window.location.href='index.php?page=home';</script>";
            exit;
        }
        include 'pages/admin.php'; 
        break;
    case 'login': include 'pages/login.php'; break;
    case 'register': include 'pages/register.php'; break;
}

include 'templates/footer.php';
?>