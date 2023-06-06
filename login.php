
<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
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

        input[type="submit"]:hover {
            background-color: #3682ff;
        }
    </style>
</head>
<body>
<h2>Вход в систему</h2>
<form action="login_handler.php" method="POST">

    <label for="username">Имя пользователя:</label>
    <input type="text" id="username" name="username" required>
    <br>
    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" name="login" value="Войти">
</form>
</body>
</html>
