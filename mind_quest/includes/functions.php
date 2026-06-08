<?php
function getQuests($pdo, $limit = null) {
    $sql = "SELECT * FROM quests WHERE is_active = 1 ORDER BY id DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getQuestById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM quests WHERE id = ? AND is_active = 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserBookings($pdo, $userId, $userEmail) {
    $stmt = $pdo->prepare("
        SELECT b.*, q.title as quest_title 
        FROM bookings b
        JOIN quests q ON b.quest_id = q.id
        WHERE b.user_id = ? OR b.contact_email = ?
        ORDER BY b.created_at DESC
    ");
    $stmt->execute([$userId, $userEmail]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBookings($pdo) {
    $stmt = $pdo->query("
        SELECT b.*, q.title as quest_title, u.full_name as user_name
        FROM bookings b
        JOIN quests q ON b.quest_id = q.id
        LEFT JOIN users u ON b.user_id = u.id
        ORDER BY b.created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>