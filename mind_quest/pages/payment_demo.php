<?php
$booking_id = $_GET['id'] ?? 0;

if (!$booking_id) {
    echo "<script>window.location.href='index.php?page=home';</script>";
    exit;
}

// Получаем информацию о бронировании
$stmt = $pdo->prepare("
    SELECT b.*, q.title as quest_title, q.price 
    FROM bookings b 
    JOIN quests q ON b.quest_id = q.id 
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "<script>window.location.href='index.php?page=home';</script>";
    exit;
}

// Обработка оплаты
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обновляем статус оплаты
    $stmt = $pdo->prepare("UPDATE bookings SET payment_status = 'paid', status = 'confirmed' WHERE id = ?");
    $stmt->execute([$booking_id]);
    
    echo "<script>alert('Оплата прошла успешно! Ваше бронирование подтверждено.'); window.location.href='index.php?page=profile';</script>";
    exit;
}
?>
<div class="container">
    <div class="booking-section">
        <h2 style="font-size: 32px; margin-bottom: 20px;">Оплата бронирования</h2>
        
        <div style="background: rgba(255,255,255,0.05); border-radius: 24px; padding: 30px; margin-bottom: 30px;">
            <h3 style="color: #ff6b35; margin-bottom: 15px;">Детали заказа</h3>
            <p><strong>Квест:</strong> <?php echo htmlspecialchars($booking['quest_title']); ?></p>
            <p><strong>Дата:</strong> <?php echo $booking['booking_date']; ?></p>
            <p><strong>Время:</strong> <?php echo $booking['booking_time']; ?></p>
            <p><strong>Сумма к оплате:</strong> <span style="font-size: 24px; color: #ff6b35;"><?php echo number_format($booking['price'], 0, '', ' '); ?> ₽</span></p>
        </div>
        
        <h3 style="margin-bottom: 20px;">Данные банковской карты (демо-режим)</h3>
        <p style="color: #888; margin-bottom: 20px;">Для тестового платежа нажмите кнопку "Оплатить"</p>
        
        <form method="POST">
            <div class="payment-form">
                <div class="form-group">
                    <label>Номер карты</label>
                    <input type="text" name="card_number" placeholder="4111 1111 1111 1111" value="4111 1111 1111 1111" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Срок действия</label>
                        <input type="text" name="expiry" placeholder="MM/YY" value="12/25" required>
                    </div>
                    <div class="form-group">
                        <label>CVV код</label>
                        <input type="password" name="cvv" placeholder="123" value="123" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Имя держателя</label>
                    <input type="text" name="holder" placeholder="IVAN IVANOV" value="IVAN IVANOV" required>
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 20px; padding: 16px;">Оплатить <?php echo number_format($booking['price'], 0, '', ' '); ?> ₽</button>
            </div>
        </form>
        
        
    </div>
</div>

<style>
.payment-form .form-group {
    margin-bottom: 20px;
}
.payment-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}
.payment-form input {
    width: 100%;
    padding: 12px 16px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    color: #fff;
    font-size: 16px;
}
.payment-form input:focus {
    outline: none;
    border-color: #ff6b35;
}
</style>