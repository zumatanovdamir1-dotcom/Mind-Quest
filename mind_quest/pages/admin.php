<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>alert('Доступ запрещен! Требуются права администратора.'); window.location.href='index.php?page=home';</script>";
    exit;
}

// Обновление статуса бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = ?, payment_status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_POST['payment_status'], $_POST['booking_id']]);
    echo "<script>alert('Статус обновлен'); window.location.href='index.php?page=admin';</script>";
    exit;
}

// Добавление нового квеста
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quest'])) {
    $stmt = $pdo->prepare("INSERT INTO quests (title, description, genre, difficulty_level, min_players, max_players, duration_minutes, age_restriction, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['title'], $_POST['description'], $_POST['genre'],
        $_POST['difficulty'], $_POST['min_players'], $_POST['max_players'],
        $_POST['duration'], $_POST['age'], $_POST['price']
    ]);
    echo "<script>alert('Квест добавлен'); window.location.href='index.php?page=admin';</script>";
    exit;
}

// Получаем все бронирования
$stmt = $pdo->query("
    SELECT b.*, q.title as quest_title, q.price 
    FROM bookings b 
    JOIN quests q ON b.quest_id = q.id 
    ORDER BY b.created_at DESC
");
$bookings = $stmt->fetchAll();

// Статистика
$stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings");
$total = $stmt->fetch();

$stmt = $pdo->query("SELECT COUNT(*) as paid FROM bookings WHERE payment_status = 'paid'");
$paid = $stmt->fetch();

$stmt = $pdo->query("SELECT SUM(price) as total_sum FROM bookings b JOIN quests q ON b.quest_id = q.id WHERE payment_status = 'paid'");
$total_sum = $stmt->fetch();
?>

<div class="container">
    <div style="margin-top: 100px;">
        <h2 style="font-size: 42px; text-align: center; margin-bottom: 10px; background: linear-gradient(135deg, #fff, #ff6b35); -webkit-background-clip: text; background-clip: text; color: transparent;">Админ панель</h2>
        <p style="text-align: center; color: #aaa; margin-bottom: 40px;">Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
    
    <!-- Статистика -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
        <div style="background: rgba(255,255,255,0.05); border-radius: 20px; padding: 20px; text-align: center;">
            <h3 style="font-size: 32px; color: #ff6b35;"><?php echo $total['total']; ?></h3>
            <p style="color: #aaa;">Всего бронирований</p>
        </div>
        <div style="background: rgba(255,255,255,0.05); border-radius: 20px; padding: 20px; text-align: center;">
            <h3 style="font-size: 32px; color: #2ecc71;"><?php echo $paid['paid']; ?></h3>
            <p style="color: #aaa;">Оплаченных</p>
        </div>
        <div style="background: rgba(255,255,255,0.05); border-radius: 20px; padding: 20px; text-align: center;">
            <h3 style="font-size: 32px; color: #ff6b35;"><?php echo number_format($total_sum['total_sum'] ?? 0, 0, '', ' '); ?> ₽</h3>
            <p style="color: #aaa;">Общая выручка</p>
        </div>
    </div>
    
    <!-- Управление бронированиями -->
    <div class="admin-section">
        <h3 style="margin-bottom: 20px; color: #fff;">Управление бронированиями</h3>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr style="border-bottom: 2px solid rgba(255,255,255,0.1);">
                        <th style="padding: 12px;">ID</th>
                        <th style="padding: 12px;">Квест</th>
                        <th style="padding: 12px;">Клиент</th>
                        <th style="padding: 12px;">Дата</th>
                        <th style="padding: 12px;">Время</th>
                        <th style="padding: 12px;">Сумма</th>
                        <th style="padding: 12px;">Статус</th>
                        <th style="padding: 12px;">Оплата</th>
                        <th style="padding: 12px;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td colspan="9" style="text-align: center; padding: 40px; color: #aaa;">Нет бронирований</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 12px;"><?php echo $booking['id']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($booking['quest_title']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($booking['contact_name']); ?></td>
                            <td style="padding: 12px;"><?php echo $booking['booking_date']; ?></td>
                            <td style="padding: 12px;"><?php echo $booking['booking_time']; ?></td>
                            <td style="padding: 12px; color: #ff6b35; font-weight: bold;"><?php echo number_format($booking['price'], 0, '', ' '); ?> ₽</td>
                            <td style="padding: 12px;">
                                <?php
                                $statusLabels = [
                                    'waiting_payment' => 'Ожидает оплаты',
                                    'new' => 'Новая',
                                    'confirmed' => 'Подтверждена',
                                    'completed' => 'Завершена',
                                    'cancelled' => 'Отменена'
                                ];
                                $statusClass = [
                                    'waiting_payment' => 'status-waiting_payment',
                                    'new' => 'status-new',
                                    'confirmed' => 'status-confirmed',
                                    'completed' => 'status-completed',
                                    'cancelled' => 'status-cancelled'
                                ];
                                ?>
                                <span class="<?php echo $statusClass[$booking['status']] ?? 'status-new'; ?>">
                                    <?php echo $statusLabels[$booking['status']] ?? $booking['status']; ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <?php
                                $paymentLabels = [
                                    'pending' => 'Не оплачен',
                                    'paid' => 'Оплачен',
                                    'cancelled' => 'Отменён'
                                ];
                                $paymentClass = [
                                    'pending' => 'payment-pending',
                                    'paid' => 'payment-paid',
                                    'cancelled' => 'payment-cancelled'
                                ];
                                ?>
                                <span class="<?php echo $paymentClass[$booking['payment_status']] ?? 'payment-pending'; ?>">
                                    <?php echo $paymentLabels[$booking['payment_status']] ?? $booking['payment_status']; ?>
                                </span>
                            </td>
                            <td style="padding: 12px;">
                                <form method="POST" style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                    <select name="status" class="admin-select">
                                        <option value="waiting_payment" <?php echo $booking['status'] === 'waiting_payment' ? 'selected' : ''; ?>>Ожидает оплаты</option>
                                        <option value="new" <?php echo $booking['status'] === 'new' ? 'selected' : ''; ?>>Новая</option>
                                        <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Подтверждена</option>
                                        <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Завершена</option>
                                        <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Отменена</option>
                                    </select>
                                    <select name="payment_status" class="admin-select">
                                        <option value="pending" <?php echo $booking['payment_status'] === 'pending' ? 'selected' : ''; ?>>Не оплачен</option>
                                        <option value="paid" <?php echo $booking['payment_status'] === 'paid' ? 'selected' : ''; ?>>Оплачен</option>
                                        <option value="cancelled" <?php echo $booking['payment_status'] === 'cancelled' ? 'selected' : ''; ?>>Отменён</option>
                                    </select>
                                    <button type="submit" name="update_booking" class="btn-update">Обновить</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Добавление нового квеста -->
    <div class="admin-section">
        <h3 style="margin-bottom: 20px; color: #fff;">Добавить новый квест</h3>
        <form method="POST">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <label>Название квеста</label>
                    <input type="text" name="title" class="admin-input" required>
                </div>
                <div>
                    <label>Жанр</label>
                    <select name="genre" class="admin-select">
                        <option value="Хоррор">Хоррор</option>
                        <option value="Детектив">Детектив</option>
                        <option value="Приключения">Приключения</option>
                        <option value="Семейный">Семейный</option>
                    </select>
                </div>
                <div>
                    <label>Цена (руб)</label>
                    <input type="number" name="price" class="admin-input" required>
                </div>
                <div>
                    <label>Сложность (1-5)</label>
                    <input type="number" name="difficulty" min="1" max="5" value="3" class="admin-input">
                </div>
                <div>
                    <label>Мин. игроков</label>
                    <input type="number" name="min_players" value="2" class="admin-input">
                </div>
                <div>
                    <label>Макс. игроков</label>
                    <input type="number" name="max_players" value="6" class="admin-input">
                </div>
                <div>
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration" value="60" class="admin-input">
                </div>
                <div>
                    <label>Возрастное ограничение</label>
                    <input type="number" name="age" value="12" class="admin-input">
                </div>
                <div style="grid-column: span 2;">
                    <label>Описание</label>
                    <textarea name="description" rows="3" class="admin-textarea" placeholder="Подробное описание квеста..."></textarea>
                </div>
            </div>
            <button type="submit" name="add_quest" class="btn-primary" style="margin-top: 20px;">Добавить квест</button>
        </form>
    </div>
</div>

<style>
.admin-section {
    background: rgba(255,255,255,0.05);
    border-radius: 24px;
    padding: 30px;
    margin-bottom: 30px;
    border: 1px solid rgba(255,255,255,0.1);
}
.admin-input, .admin-textarea {
    width: 100%;
    padding: 10px 14px;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
}
.admin-select {
    width: 100%;
    padding: 10px 14px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px;
    color: #ffffff;
    font-size: 14px;
    cursor: pointer;
}
.admin-select option {
    background: #1a1a2e;
    color: #ffffff;
    padding: 10px;
}
.admin-select option:hover {
    background: #ff6b35;
}
.admin-input:focus, .admin-select:focus, .admin-textarea:focus {
    outline: none;
    border-color: #ff6b35;
}
.admin-section label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #aaa;
}
.admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.admin-table th, .admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}
.admin-table th {
    color: #ff6b35;
    font-weight: 600;
}
.btn-update {
    background: #ff6b35;
    border: none;
    padding: 6px 16px;
    border-radius: 8px;
    color: #0a0a0a;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}
.btn-update:hover {
    background: #ff8855;
}
.status-waiting_payment {
    background: #f39c12;
    color: #0a0a0a;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-new {
    background: #3498db;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-confirmed {
    background: #2ecc71;
    color: #0a0a0a;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-completed {
    background: #27ae60;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.status-cancelled {
    background: #e74c3c;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.payment-pending {
    background: #e74c3c;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.payment-paid {
    background: #2ecc71;
    color: #0a0a0a;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
.payment-cancelled {
    background: #95a5a6;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}
</style>