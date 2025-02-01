<?php
function adminMiddleware()
{
    session_start();

    if (!isset($_SESSION['userId']) || $_SESSION['user']['role'] !== 'admin') {
        exit();
    }
}
