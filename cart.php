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

// Оформление заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $delivery_method = $_POST['delivery_method'];

    // Получение информации о товарах в корзине
    $sql = "SELECT cp.product_id, cp.quantity, p.name, p.price FROM cart_product cp JOIN product p ON cp.product_id = p.id WHERE cp.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_price = 0;

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];
        $name = $row['name'];
        $price = $row['price'];

        // Вычисление общей стоимости заказа
        $total_price += $price * $quantity;

        // Создание записи о заказе в таблице orders
        $stmt = $conn->prepare("INSERT INTO orders (user_id, delivery_method, total_price, status) VALUES (?, ?, ?, 'в процессе')");
        $stmt->bind_param("iss", $user_id, $delivery_method, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        // Создание записи о товаре в таблице order_product
        $stmt = $conn->prepare("INSERT INTO order_product (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $order_id, $product_id, $quantity);
        $stmt->execute();

        // Удаление товара из таблицы cart_product
        $stmt = $conn->prepare("DELETE FROM cart_product WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    // Перенаправление на страницу успешного оформления заказа
    header("Location: order_success.php?order_id=$order_id");
    exit();
}

// Получение информации о товарах в корзине из таблицы cart_product
$user_id = $_SESSION['user_id'];

$sql = "SELECT cp.product_id, cp.quantity, p.name, p.price FROM cart_product cp JOIN product p ON cp.product_id = p.id WHERE cp.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = array();

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Корзина</title>
    <style>
        body {
            background-color: #f0f8ff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #f0f8ff;
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

        .cart-container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .cart-container h1 {
            color: #1e90ff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #1e90ff;
        }

        .cart-table th {
            background-color: #1e90ff;
            color: #fff;
        }

        .cart-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .cart-actions button {
            background-color: #1e90ff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
        }

        .empty-cart-message {
            color: #1e90ff;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="navbar">
        <header>
            <div class="logo">
                <h1>Vitalix</h1>
            </div>
        </header>

        <div class="navbar-right">
            <?php
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                // Пользователь авторизован
                echo 'Добро пожаловать, ' . $_SESSION['username'] . ' | ';
                echo '<a href="logout.php">Выйти</a>';
                echo '<a href="cart.php" class="cart-link">Корзина</a>';
                echo '<a href="orders.php">Заказы</a>';
            } else {
                // Пользователь не авторизован
                echo '<a href="login.php">Войти</a>';
                echo '<a href="registration.php">Регистрация</a>';
            }
            ?>

        </div>
    </div>

    <div class="cart-container">
        <h1>Корзина</h1>

        <?php if (!empty($cart_items)) { ?>
            <table class="cart-table">
                <tr>
                    <th>Наименование</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($cart_items as $item) { ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['price']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                <button type="submit" name="remove_from_cart">Удалить</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <br>
            <form method="post" action="">
                <button type="submit" name="clear_cart">Очистить корзину</button>
                <br>
                <label for="delivery_method">Способ доставки:</label>
                <select id="delivery_method" name="delivery_method">
                    <option value="Самовывоз">Самовывоз</option>
                    <option value="Доставка">Доставка</option>
                </select>
                <br>
                <button type="submit" name="place_order">Оформить заказ</button>
            </form>
        <?php } else {
            echo "<p class='empty-cart-message'>Корзина пуста</p>";
        } ?>
    </div>
</div>

</body>
</html>
