<?php
$quests = getQuests($pdo);
?>
<div class="container">
    <div style="margin-top: 100px; text-align: center;">
        <h2 style="font-size: 42px; margin-bottom: 20px; background: linear-gradient(135deg, #fff, #ff6b35); -webkit-background-clip: text; background-clip: text; color: transparent;">Все квесты</h2>
        <p style="color: #aaa;">Выберите сюжет и погрузитесь в атмосферу приключений</p>
    </div>
    
    <div class="quests-grid">
        <?php foreach ($quests as $quest): ?>
        <div class="quest-card" onclick="window.location.href='index.php?page=booking&id=<?php echo $quest['id']; ?>'">
            <div class="quest-info">
                <h3 class="quest-title"><?php echo htmlspecialchars($quest['title']); ?></h3>
                <div class="quest-meta">
                    <span><i class="fas fa-users"></i> <?php echo $quest['min_players']; ?>-<?php echo $quest['max_players']; ?> чел</span>
                    <span><i class="fas fa-clock"></i> <?php echo $quest['duration_minutes']; ?> мин</span>
                    <span><i class="fas fa-shield-alt"></i> <?php echo $quest['age_restriction']; ?>+</span>
                </div>
                <div class="price"><?php echo number_format($quest['price'], 0, '', ' '); ?> ₽</div>
                <button class="btn-primary" style="width: 100%;">Забронировать</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>