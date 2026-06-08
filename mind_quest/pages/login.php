<?php
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
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
        echo "<script>window.location.href='index.php?page=profile';</script>";
        exit;
    } else {
        $error = "Неверный email или пароль";
    }
}
?>
<div class="container">
    <div class="auth-box">
        <h2 style="text-align: center; margin-bottom: 30px;">Вход в аккаунт</h2>
        <?php if ($error): ?>
            <p style="color: #ff6b35; text-align: center; margin-bottom: 20px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" class="btn-primary" style="width: 100%;">Войти</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            Нет аккаунта? <a href="index.php?page=register" style="color: #ff6b35;">Зарегистрироваться</a>
        </p>
    </div>
</div>