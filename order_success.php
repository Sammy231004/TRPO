<!DOCTYPE html>
<html>
<head>
    <title>Успешный заказ</title>
    <style>
        body {
            background-color: #1e90ff;
            color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-top: 0;
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
<h1>Успешный заказ</h1>
<div class="order-details">
    <?php
    // Подключение к базе данных
    $servername = "localhost";
    $username = "vitalix";
    $password = "1234";
    $dbname = "vitalix";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка подключения к базе данных
    if ($conn->connect_error) {
        die("Ошибка подключения к базе данных: " . $conn->connect_error);
    }

    // Получение данных о заказе из базы данных
    $order_id = $_GET['order_id'];

    // Запрос в базу данных
    $sql = "SELECT * FROM orders WHERE order_id = $order_id";
    $result = $conn->query($sql);

    // Проверка результатов запроса и вывод информации о заказе
    if ($result && $result->num_rows > 0) {
        // Вывод информации о заказе
        $row = $result->fetch_assoc();
        $total_price = $row['total_price'];
        $delivery_method = $row['delivery_method'];
        $status = $row['status'];

        echo "<p>Номер заказа: $order_id</p>";
        echo "<p>Общая стоимость: $total_price руб.</p>";
        echo "<p>Метод доставки: $delivery_method</p>";
        echo "<p>Статус заказа: $status</p>";
    } else {
        echo "Заказ не найден.";
        exit;
    }
    // Закрытие соединения с базой данных
    $conn->close();
    ?>
</div>
</body>
</html>
