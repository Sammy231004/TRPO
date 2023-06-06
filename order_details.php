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

// Получаем order_id из параметра запроса
if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Проверяем, принадлежит ли заказ текущему пользователю
$sql = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result_order = $stmt->get_result();

if ($result_order->num_rows === 0) {
    header("Location: orders.php");
    exit();
}

// Получаем информацию о заказе из базы данных
$order = $result_order->fetch_assoc();

// Получаем информацию о товарах в заказе
$sql = "SELECT p.brand, p.photo, op.quantity 
        FROM order_product op 
        JOIN product p ON op.product_id = p.id 
        WHERE op.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result_products = $stmt->get_result();

$products = array();

while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}
?>

<!DOCTYPE html>
<head>
    <title>Детали заказа</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
        }

        .order-details-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        }

        .order-details-container h2 {
            color: #1e90ff;
            margin-top: 0;
        }

        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .product-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 10px;
            border-radius: 5px;
        }

        .product-item .product-info {
            display: flex;
            flex-direction: column;
        }

        .product-item .product-info p {
            margin: 0;
            margin-bottom: 5px;
        }
        .back-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
<div class="order-details-container">
    <h2>Детали заказа №<?php echo $order['order_id']; ?></h2>
    <p><strong>Общая стоимость:</strong> <?php echo $order['total_price']; ?> руб.</p>
    <p><strong>Метод доставки:</strong> <?php echo $order['delivery_method']; ?></p>
    <p><strong>Статус:</strong> <?php echo $order['status']; ?></p>
    <h3>Товары в заказе:</h3>
    <?php
    foreach ($products as $product) {
        echo '<div class="product-item">';
        echo '<img src="' . $product['photo'] . '" alt="' . $product['brand'] . '">';
        echo '<div class="product-info">';
        echo '<p><strong>Бренд:</strong> ' . $product['brand'] . '</p>';
        echo '<p><strong>Количество:</strong> ' . $product['quantity'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    ?>

</div>

</body>
<a class="back-button" href="orders.php">&larr; Назад</a>
</html>
