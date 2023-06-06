<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>Vitalix - Магазин бытовой техники</title>
    <style>
        .navbar {
            background-color: #f1f1f1;
            overflow: hidden;
        }
        .logo {
            background-color: #f1f1f1;
            overflow: hidden;
            display: inline-block;
            padding: 1px 1px;
            color: #555;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar-right {
            float: right;
        }

        .navbar-right a {
            display: inline-block;
            padding: 20px 30px;
            color: #555;
            text-decoration: none;
            font-weight: bold;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }


    </style>
</head>
<body>
<div class="logo">
    <h1>Vitalix</h1>
</div>
<div class="navbar">
    <div class="navbar-right">


        <?php
        session_start();
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            // Пользователь авторизован
            echo 'Добро пожаловать, ' . $_SESSION['username'] . ' | ';
            echo '<a href="logout.php">Выйти</a>';
            echo '<a href="cart.php" class="cart-link">Корзина</a>';
            echo '<a href="orders.php">Заказы</a>';
            // Проверяем роль пользователя
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'Администратор') {
                echo '<a href="admin.php">Панель администратора</a>';
            }
        } else {
            // Пользователь не авторизован
            echo '<a href="login.php">Войти</a>';
            echo '<a href="registration.php">Регистрация</a>';

        }
        ?>

    </div>
</div>




        <section class="hero">
            <div class="hero-content">
                <h2>Лучшая бытовая техника для вашего дома</h2>
                <p>Выбирайте из широкого ассортимента качественных товаров по выгодным ценам</p>
        <a href="catalog.php" class="btn">Перейти в каталог</a>
    </div>
</section>

<section class="featured-products">
</section>

<footer>
    <p>&copy; 2023 Vitalix. Все права защищены.</p>
</footer>

</body>
</html>