<?php
$quests = getQuests($pdo, 3);
?>
<div class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h2>НОВОЕ ИЗМЕРЕНИЕ<br>ПРИКЛЮЧЕНИЙ</h2>
                <p>Живые квесты, командные расследования, хоррор и мистика. Бронируй онлайн — погружайся в историю без лишних звонков.</p>
                <a href="index.php?page=quests" class="btn-primary">Начать игру</a>
                <div class="hero-stats">
                    <div class="stat"><h3>24/7</h3><p>Запись онлайн</p></div>
                    <div class="stat"><h3>15+</h3><p>Сценариев</p></div>
                    <div class="stat"><h3>98%</h3><p>Восторг игроков</p></div>
                </div>
            </div>
            <div class="hero-image">
                <i class="fas fa-skull"></i>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="section-header">
        <h2>Популярные квесты</h2>
        <a href="index.php?page=quests" class="btn-secondary">Смотреть все</a>
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