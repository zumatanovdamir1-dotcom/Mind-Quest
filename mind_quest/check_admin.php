<?php
echo "<h1>Проверка сервера</h1>";
echo "<p>Файл работает!</p>";

// Подключаемся к базе данных
$host = 'localhost';
$dbname = 'mind_quest';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Подключение к базе данных успешно!</p>";
    
    // Проверяем пользователей
    $stmt = $pdo->query("SELECT id, email, full_name, role FROM users");
    $users = $stmt->fetchAll();
    
    echo "<h2>Пользователи в базе:</h2>";
    if (empty($users)) {
        echo "<p style='color: red;'>⚠️ Нет ни одного пользователя!</p>";
        
        // Создаём админа
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, full_name, phone, role) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->execute(['admin@mindquest.ru', $hash, 'Администратор', '+79000000000']);
        echo "<p style='color: green;'>✅ Администратор создан автоматически!</p>";
        
        // Снова получаем список
        $stmt = $pdo->query("SELECT id, email, full_name, role FROM users");
        $users = $stmt->fetchAll();
    }
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Email</th><th>Имя</th><th>Роль</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . $user['email'] . "</td>";
        echo "<td>" . $user['full_name'] . "</td>";
        echo "<td>" . $user['role'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Ошибка БД: " . $e->getMessage() . "</p>";
}
?>