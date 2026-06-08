<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_phone'] = $user['phone'];
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Неверный email или пароль']);
    }
    exit;
}

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['full_name'];
    $phone = $_POST['phone'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name, phone) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, $password, $name, $phone]);
        echo json_encode(['success' => true]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Email уже зарегистрирован']);
    }
    exit;
}

if ($action === 'book' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id = $_POST['slot_id'];
    $quest_id = $_POST['quest_id'];
    $contact_name = $_POST['contact_name'];
    $contact_phone = $_POST['contact_phone'];
    $contact_email = $_POST['contact_email'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("SELECT status FROM time_slots WHERE id = ? FOR UPDATE");
        $stmt->execute([$slot_id]);
        $slot = $stmt->fetch();
        
        if (!$slot || $slot['status'] !== 'free') {
            throw new Exception('Слот уже забронирован');
        }
        
        $pdo->prepare("UPDATE time_slots SET status = 'booked' WHERE id = ?")->execute([$slot_id]);
        
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, quest_id, slot_id, contact_name, contact_phone, contact_email, comment, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'new')");
        $stmt->execute([$user_id, $quest_id, $slot_id, $contact_name, $contact_phone, $contact_email, $comment]);
        
        $booking_id = $pdo->lastInsertId();
        $pdo->commit();
        
        echo json_encode(['success' => true, 'booking_id' => $booking_id]);
    } catch(Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($action === 'add_review' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        echo json_encode(['success' => false, 'error' => 'Необходимо авторизоваться']);
        exit;
    }
    
    $booking_id = $_POST['booking_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $text = $_POST['text'];
    
    $stmt = $pdo->prepare("INSERT INTO reviews (booking_id, user_id, rating, text, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$booking_id, $user_id, $rating, $text]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'admin_update_booking' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAdmin()) { echo json_encode(['success' => false]); exit; }
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->execute([$status, $booking_id]);
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'admin_update_review' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAdmin()) { echo json_encode(['success' => false]); exit; }
    $review_id = $_POST['review_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE reviews SET status = ? WHERE id = ?");
    $stmt->execute([$status, $review_id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
?>