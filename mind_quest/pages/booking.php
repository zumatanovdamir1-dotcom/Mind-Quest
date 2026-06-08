<?php
$quest_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM quests WHERE id = ?");
$stmt->execute([$quest_id]);
$quest = $stmt->fetch();

if (!$quest) {
    header('Location: index.php?page=quests');
    exit;
}

$date = $_GET['date'] ?? date('Y-m-d');
$slots = ['10:00', '12:30', '15:00', '17:30', '20:00', '22:00'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $slot_time = $_POST['slot_time'];
    $booking_date = $_POST['booking_date'];
    
    $stmt = $pdo->prepare("INSERT INTO bookings (quest_id, contact_name, contact_phone, contact_email, comment, status, user_id) VALUES (?, ?, ?, ?, ?, 'new', ?)");
    $stmt->execute([$quest_id, $name, $phone, $email, $comment, $_SESSION['user_id'] ?? null]);
    $booking_id = $pdo->lastInsertId();
    
    echo "<script>window.location.href='index.php?page=payment_demo&id=$booking_id';</script>";
    exit;
}
?>
<div class="container">
    <div class="booking-section">
        <h2 style="font-size: 32px; margin-bottom: 10px;">Бронирование квеста</h2>
        <h3 style="font-size: 24px; color: #ff6b35; margin-bottom: 30px;"><?= htmlspecialchars($quest['title']) ?></h3>
        
        <div style="background: rgba(255,255,255,0.05); border-radius: 16px; padding: 20px; margin-bottom: 30px;">
            <p style="font-size: 18px;">Стоимость: <strong style="color: #ff6b35; font-size: 24px;"><?= number_format($quest['price'], 0, '', ' ') ?> ₽</strong></p>
            <p style="color: #888; font-size: 14px;">Оплата банковской картой после подтверждения бронирования</p>
        </div>
        
        <form method="POST">
            <div class="booking-grid">
                <!-- Левая колонка - дата и время -->
                <div>
                    <div style="margin-bottom: 25px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 10px; color: #ff6b35; font-size: 14px;">ДАТА СЕАНСА</label>
                        <input type="date" name="booking_date" value="<?= $date ?>" min="<?= date('Y-m-d') ?>" required style="width: 100%; padding: 14px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #fff; font-size: 16px;">
                    </div>
                    
                    <div>
                        <label style="font-weight: 600; display: block; margin-bottom: 10px; color: #ff6b35; font-size: 14px;">ДОСТУПНОЕ ВРЕМЯ</label>
                        <div class="time-slots" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                            <?php foreach ($slots as $slot): ?>
                            <div class="slot" data-time="<?= $slot ?>" style="text-align: center; padding: 12px; background: rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; transition: 0.3s; font-weight: 500;"><?= $slot ?></div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="slot_time" id="selected_time" required>
                    </div>
                </div>
                
                <!-- Правая колонка - контактные данные -->
                <div>
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 8px; color: #ff6b35; font-size: 14px;">ВАШЕ ИМЯ</label>
                        <input type="text" name="name" placeholder="Алексей" value="<?= $_SESSION['user_name'] ?? '' ?>" required style="width: 100%; padding: 14px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #fff; font-size: 16px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 8px; color: #ff6b35; font-size: 14px;">EMAIL</label>
                        <input type="email" name="email" placeholder="your@email.com" value="<?= $_SESSION['user_email'] ?? '' ?>" required style="width: 100%; padding: 14px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #fff; font-size: 16px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 8px; color: #ff6b35; font-size: 14px;">ТЕЛЕФОН</label>
                        <input type="tel" name="phone" placeholder="+7 999 777 22 11" value="<?= $_SESSION['user_phone'] ?? '' ?>" required style="width: 100%; padding: 14px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #fff; font-size: 16px;">
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label style="font-weight: 600; display: block; margin-bottom: 8px; color: #ff6b35; font-size: 14px;">КОММЕНТАРИЙ (ПОЖЕЛАНИЯ)</label>
                        <textarea name="comment" rows="4" placeholder="Ваши пожелания..." style="width: 100%; padding: 14px 18px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #fff; font-size: 16px; resize: vertical;"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 16px; font-size: 18px; font-weight: 700; background: #ff6b35; border: none; border-radius: 40px; color: #0a0a0a; cursor: pointer; transition: 0.3s; margin-top: 10px;">ПРОДОЛЖИТЬ ОФОРМЛЕНИЕ</button>
                    <p style="text-align: center; margin-top: 16px; font-size: 13px; color: #888;">Далее вы сможете оплатить заказ</p>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.booking-section {
    background: rgba(255,255,255,0.05);
    border-radius: 32px;
    padding: 40px;
    margin: 100px 0;
    border: 1px solid rgba(255,255,255,0.1);
}

.booking-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
}

.slot {
    transition: all 0.3s ease;
}

.slot:hover {
    background: #ff6b35 !important;
    color: #0a0a0a !important;
    transform: translateY(-2px);
}

.slot.selected {
    background: #ff6b35;
    color: #0a0a0a;
}

input, textarea {
    transition: all 0.3s ease;
}

input:focus, textarea:focus {
    outline: none;
    border-color: #ff6b35;
    box-shadow: 0 0 10px rgba(255,107,53,0.3);
}

@media (max-width: 768px) {
    .booking-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .time-slots {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
</style>

<script>
document.querySelectorAll('.slot').forEach(slot => {
    slot.addEventListener('click', function() {
        document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selected_time').value = this.dataset.time;
    });
});
</script>