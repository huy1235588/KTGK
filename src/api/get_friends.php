<?php
session_start();
require 'db.php';

$currentUserId = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT u.id, u.username, u.avatar, u.last_online 
    FROM friend_requests fr
    JOIN users u ON u.id = IF(fr.sender_id = ?, fr.receiver_id, fr.sender_id)
    WHERE (fr.sender_id = ? OR fr.receiver_id = ?) 
    AND fr.status = 'accepted'
");
$stmt->execute([$currentUserId, $currentUserId, $currentUserId]);
$friends = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($friends);
