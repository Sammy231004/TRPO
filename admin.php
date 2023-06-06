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

// Проверяем, является ли текущий пользователь администратором
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Администратор') {
    // Если пользователь не является администратором, перенаправляем его на другую страницу или выводим сообщение об ошибке
    header('Location: access_denied.php');
    exit();
}

// Подключаемся к базе данных
$servername = "localhost";
$username = "vitalix";
$password = "1234";
$dbname = "vitalix";


$conn = mysqli_connect($servername, $username, $password, $dbname);

// Проверяем, удалось ли подключиться к базе данных
if (!$conn) {
    die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
}

// Обработка формы добавления товара
if (isset($_POST['add_product'])) {
    $brand = $_POST['brand'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $photo = $_POST['photo'];
    $price = $_POST['price'];

    // Выполняем запрос на добавление товара
    $sql = "INSERT INTO product (brand, category, name, photo, price) VALUES ('$brand', '$category', '$name', '$photo', $price)";

    if (mysqli_query($conn, $sql)) {
        echo 'Товар успешно добавлен.';
    } else {
        echo 'Ошибка при добавлении товара: ' . mysqli_error($conn);
    }
}

// Обработка формы изменения статуса заказа
if (isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Выполняем запрос на изменение статуса заказа
    $sql = "UPDATE orders SET status = '$status' WHERE order_id = $order_id";

    if (mysqli_query($conn, $sql)) {
        echo 'Статус заказа успешно изменен.';
    } else {
        echo 'Ошибка при изменении статуса заказа: ' . mysqli_error($conn);
    }
}

// Обработка формы удаления товара
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Выполняем запрос на удаление товара
    $sql = "DELETE FROM product WHERE id = $product_id";

    if (mysqli_query($conn, $sql)) {
        echo 'Товар успешно удален.';
    } else {
        echo 'Ошибка при удалении товара: ' . mysqli_error($conn);
    }
}

// Получаем все заказы из базы данных
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);

// Получаем всех пользователей из базы данных
$sql_users = "SELECT * FROM users";
$result_users = mysqli_query($conn, $sql_users);

?>

<!DOCTYPE html>
<html>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    h1, h2 {
        color: #333;
    }

    form {
        margin-bottom: 20px;
    }

    label {
        display: inline-block;
        width: 100px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 250px;
        padding: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        padding: 10px 20px;
        background-color: #4e7eff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    a {
        color: #4CAF50;
        text-decoration: none;
    }
</style>
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
</head>
<body>
<h1>Панель администратора</h1>

<!-- Форма добавления товара -->
<h2>Добавить новый товар</h2>
<form method="POST" action="">
    <label for="brand">Бренд:</label>
    <input type="text" name="brand" id="brand" required><br>

    <label for="category">Категория:</label>
    <input type="text" name="category" id="category" required><br>

    <label for="name">Название:</label>
    <input type="text" name="name" id="name" required><br>

    <label for="photo">Фото:</label>
    <input type="text" name="photo" id="photo" required><br>

    <label for="price">Цена:</label>
    <input type="number" name="price" id="price" required><br>

    <input type="submit" name="add_product" value="Добавить товар">
</form>

<!-- Форма изменения статуса заказа -->
<h2>Изменить статус заказа</h2>
<form method="POST" action="">
    <label for="order_id">ID заказа:</label>
    <input type="number" name="order_id" id="order_id" required><br>

    <label for="status">Статус:</label>
    <select name="status" id="status" required>
        <option value="В обработке">В обработке</option>
        <option value="Выполняется">Выполняется</option>
        <option value="Доставляется">Доставляется</option>
        <option value="Завершен">Завершен</option>
    </select><br>

    <input type="submit" name="update_order_status" value="Изменить статус">
</form>

<!-- Форма удаления товара -->
<h2>Удалить товар</h2>
<form method="POST" action="">
    <label for="product_id">ID товара:</label>
    <input type="number" name="product_id" id="product_id" required><br>

    <input type="submit" name="delete_product" value="Удалить товар">
</form>

<!-- Список заказов -->
<?php
if (mysqli_num_rows($result) > 0) {
    echo '<h2>Список заказов</h2>';
    echo '<table>';
    echo '<tr><th>Идентификатор заказа</th><th>Идентификатор пользователя</th><th>Общая стоимость</th><th>Статус</th></tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['order_id'] . '</td>';
        echo '<td>' . $row['user_id'] . '</td>';
        echo '<td>' . $row['total_price'] . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'Нет доступных заказов.';
}
?>

<!-- Список пользователей -->
<?php
if (mysqli_num_rows($result_users) > 0) {
    echo '<h2>Список пользователей</h2>';
    echo '<table>';
    echo '<tr><th>Идентификатор пользователя</th><th>Имя</th><th>Фамилия</th><th>Email</th></tr>';

    while ($row_users = mysqli_fetch_assoc($result_users)) {
        echo '<tr>';
        echo '<td>' . $row_users['user_id'] . '</td>';
        echo '<td>' . $row_users['customer_fname'] . '</td>';
        echo '<td>' . $row_users['customer_lname'] . '</td>';
        echo '<td>' . $row_users['email'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'Нет доступных пользователей.';
}
?>

<a href="logout.php">Выйти</a>
</body>
</html>
