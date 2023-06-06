<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>

</head>
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

    button[type="submit"] {
        width: 75%;
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
<body>

<div class="container">
    <h1>Регистрация</h1>
    <form action="registration_handler.php" method="post">
        <div class="form-group">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="customer_fname">Имя:</label>
            <input type="text" id="customer_fname" name="customer_fname" required>
        </div>
        <div class="form-group">
            <label for="customer_lname">Фамилия:</label>
            <input type="text" id="customer_lname" name="customer_lname" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <button type="submit" name="register">Зарегистрироваться</button>
    </form>
</div>
</body>

</html>