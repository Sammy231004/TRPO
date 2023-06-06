<?php
session_start();

$servername = "localhost";
$username = "vitalix";
$password = "1234";
$dbname = "vitalix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверяем, существует ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Получение заказов пользователя из базы данных
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = array();

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Мои заказы</title>
    <style>
        body {
            background-color: #f0f8ff;
        }

        header {
            background-color: #f0f8ff;
            padding: 10px;
            text-align: left;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .navbar {
            background-color: #1e90ff;
            overflow: hidden;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        .navbar-right {
            float: right;
        }

        .navbar-right a {
            display: inline-block;
            padding: 20px 30px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .orders-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .orders-container h2 {
            color: #1e90ff;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #1e90ff;
        }

        .orders-table th {
            background-color: #1e90ff;
            color: #fff;
        }

        .order-details {
            margin-top: 20px;
        }

        .order-details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-right">
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            // Пользователь авторизован
            echo 'Добро пожаловать, ' . $_SESSION['username'] . ' | ';
            echo '<a href="logout.php">Выйти</a>';
            echo '<a href="cart.php" class="cart-link">Корзина</a>';
        } else {
            // Пользователь не авторизован
            echo '<a href="login.php">Войти</a>';
            echo '<a href="register.php">Зарегистрироваться</a>';
        }
        ?>
    </div>
</div>

<div class="orders-container">
    <h2>Мои заказы</h2>
    <?php
    if (count($orders) > 0) {
        echo '<table class="orders-table">';
        echo '<tr><th>Номер заказа</th><th>Общая стоимость</th><th>Метод доставки</th><th>Статус</th></tr>';

        foreach ($orders as $order) {
            echo '<tr>';
            echo '<td><a href="order_details.php?order_id=' . $order['order_id'] . '">' . $order['order_id'] . '</a></td>';
            echo '<td>' . $order['total_price'] . ' руб.</td>';
            echo '<td>' . $order['delivery_method'] . '</td>';
            echo '<td>' . $order['status'] . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>У вас пока нет заказов.</p>';
    }
    ?>
</div>
</body>
</html>
