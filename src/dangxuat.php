<?php
session_start();

// Xóa tất cả các session
session_unset();
session_destroy();

$currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
header("Location: {$redirect}");
exit();