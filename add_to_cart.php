<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// Получаем идентификатор товара из POST-параметра
$product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';

// Получаем идентификатор пользователя из POST-параметра
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

// Проверяем, являются ли $product_id и $user_id числами
if (!is_numeric($product_id) || !is_numeric($user_id)) {
    // Если $product_id или $user_id не являются числами, перенаправляем пользователя обратно на страницу товаров
    header("Location: catalog.php");
    exit;
}

// Подключаемся к базе данных
$conn = new mysqli("localhost", "vitalix", "1234", "vitalix");

// Проверяем соединение с базой данных
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверяем, существует ли товар с указанным идентификатором в базе данных
$sql = "SELECT * FROM product WHERE id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Товар существует, добавляем его в таблицу cart_product

    // Проверяем, находится ли товар уже в корзине пользователя
    $sql = "SELECT * FROM cart_product WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Товар уже находится в корзине, увеличиваем количество
        $sql = "UPDATE cart_product SET quantity = quantity + 1 WHERE product_id = '$product_id' AND user_id = '$user_id'";
    } else {
        // Товар не находится в корзине, добавляем его с количеством 1
        $sql = "INSERT INTO cart_product (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
    }

    if ($conn->query($sql) === true) {
        // Товар успешно добавлен в корзину
        echo "Товар успешно добавлен в корзину!";
    } else {
        // Произошла ошибка при добавлении товара в корзину
        echo "Ошибка при добавлении товара в корзину: " . $conn->error;
    }
} else {
    // Товар не найден в базе данных
    echo "Товар не найден.";
}

$conn->close();

?>
