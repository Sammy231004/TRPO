<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        text-align: center;
    }
    h2 {
        text-align: center;
    }

    form {
        max-width: 300px;
        text-align: center;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border-radius: 3px;
        border: 1px solid #ccc;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: #4e7eff;
        border: none;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        border-radius: 30px;

    }

    input[type="done"]:hover {
        background-color: #3682ff;
    }
</style>
<?php
$servername = "localhost";
$username = "vitalix";
$password = "1234";
$dbname = "vitalix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка, не занято ли выбранное пользовательское имя
    $query = "SELECT * FROM users WHERE Login = '$username'";
    $result = $conn->query($query);
    $existingUser = $result->fetch_assoc();

    if ($existingUser) {
        // Пользовательское имя уже занято
        $error = "Выбранное имя пользователя уже занято";
    } else {
        $customer_fname = $_POST['customer_fname'];
        $customer_lname = $_POST['customer_lname'];
        $role = "Покупатель";
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Хеширование пароля
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Добавление пользователя в базу данных
        $query = "INSERT INTO users (Login, Password, customer_fname, customer_lname, role, email, phone)
                  VALUES ('$username', '$hashedPassword', '$customer_fname', '$customer_lname', '$role', '$email', '$phone')";
        $result = $conn->query($query);

        if ($result) {
            // Перенаправление на страницу после успешной регистрации
            header("Location: login.php");
            exit();
        } else {
            // Обработка ошибки добавления пользователя
            $error = "Ошибка при регистрации пользователя";
        }
    }
}

$conn->close();
?>

