<?php
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php?page=login';</script>";
    exit;
}

$bookings = getUserBookings($pdo, $_SESSION['user_id'], $_SESSION['user_email']);
?>
<div class="container">
    <div class="profile-section">
        <h1>Личный кабинет</h1>
        <div class="profile-info" style="margin-bottom: 30px;">
            <h3>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <p>Телефон: <?php echo htmlspecialchars($_SESSION['user_phone']); ?></p>
        </div>
        
        <div class="profile-bookings">
            <h2>Мои бронирования</h2>
            <?php if (empty($bookings)): ?>
                <p style="text-align: center; padding: 40px;">У вас пока нет бронирований</p>
                <div style="text-align: center;">
                    <a href="index.php?page=quests" class="btn-primary">Выбрать квест</a>
                </div>
            <?php else: ?>
                <div class="bookings-list">
                    <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item">
                        <div>
                            <strong><?php echo htmlspecialchars($booking['quest_title']); ?></strong><br>
                            <span style="font-size: 14px; color: #aaa;"><?php echo $booking['booking_date']; ?> в <?php echo $booking['booking_time']; ?></span>
                        </div>
                        <div>
                            <span class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></span>
                        </div>
                        <div>
                            <a href="index.php?page=booking&id=<?php echo $booking['quest_id']; ?>" class="btn-secondary" style="padding: 6px 16px; font-size: 14px;">Повторить</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>