<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Mind Quest | Сюжетные игры в реальности</title>
    
    <link rel="stylesheet" href="assets/css/style.css?v=3">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #ffffff; line-height: 1.5; }
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        header { background: rgba(10,10,10,0.95); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 0; flex-wrap: wrap; }
        .logo a { text-decoration: none; }
        .logo h1 { font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg, #fff, #ff6b35); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .nav-links { display: flex; gap: 32px; align-items: center; flex-wrap: wrap; }
        .nav-links a { text-decoration: none; color: #fff; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: #ff6b35; }
        .btn-outline-light { border: 1.5px solid #ff6b35; background: transparent; padding: 8px 20px; border-radius: 40px; color: #ff6b35; font-weight: 600; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-outline-light:hover { background: #ff6b35; color: #0a0a0a; }
        .btn-primary { background: #ff6b35; border: none; padding: 12px 28px; border-radius: 40px; color: #0a0a0a; font-weight: 700; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary:hover { background: #ff8855; transform: translateY(-2px); }
        footer { background: #0a0a0a; border-top: 1px solid rgba(255,255,255,0.1); padding: 60px 0 30px; margin-top: 80px; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; }
        .footer-section h4 { color: #ff6b35; margin-bottom: 20px; }
        .footer-section p { color: #888; margin: 8px 0; }
        .footer-bottom { text-align: center; padding-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); color: #555; }
        @media (max-width: 768px) { .navbar { flex-direction: column; gap: 16px; } .container { padding: 0 20px; } }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <a href="index.php?page=home"><h1>MIND QUEST</h1></a>
            </div>
            <div class="nav-links">
                <a href="index.php?page=home">Главная</a>
                <a href="index.php?page=quests">Квесты</a>
                <a href="#footer">Контакты</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=profile">Личный кабинет</a>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?page=admin" style="border: 1px solid #ff6b35; padding: 8px 20px; border-radius: 40px;">Админ панель</a>
                    <?php endif; ?>
                    <a href="index.php?page=logout" class="btn-outline-light">Выйти</a>
                <?php else: ?>
                    <a href="index.php?page=login" class="btn-outline-light">Вход / Регистрация</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<main>