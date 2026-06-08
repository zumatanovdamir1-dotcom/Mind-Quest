<?php
$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $password]);
        $success = true;
    } catch(PDOException $e) {
        $error = "Email уже зарегистрирован";
    }
}
?>
<div class="container">
    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 30px;">Регистрация</h2>
        
        <?php if ($success): ?>
            <div style="background: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; padding: 15px; border-radius: 16px; margin-bottom: 20px; text-align: center;">
                <p style="color: #2ecc71;">Регистрация успешна! Перенаправление на вход...</p>
            </div>
            <script>setTimeout(function() { window.location.href = 'index.php?page=login'; }, 2000);</script>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p style="color: #ff6b35; text-align: center; margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <?php if (!$success): ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Телефон" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" class="btn-primary" style="width: 100%;">Зарегистрироваться</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            Уже есть аккаунт? <a href="index.php?page=login" style="color: #ff6b35;">Войти</a>
        </p>
        <?php endif; ?>
    </div>
</div>