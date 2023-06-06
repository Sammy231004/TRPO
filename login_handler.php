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

// Обработка формы авторизации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка наличия имени пользователя и пароля
    if (empty($username) || empty($password)) {
        $error = "Введите имя пользователя и пароль";
    } else {
        // Поиск пользователя в базе данных
        $query = $conn->prepare("SELECT * FROM Users WHERE Login = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                // Авторизация успешна, сохранение информации о пользователе в сессии
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['Login'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                // Перенаправление на главную страницу после успешной авторизации
                header("Location: index.php");
                exit();
            } else {
                // Неверный пароль
                $error = "Неверное имя пользователя или пароль";
            }
        } else {
            // Пользователь не найден
            $error = "Неверное имя пользователя или пароль";
        }
    }
}
?>
