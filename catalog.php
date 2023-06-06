<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/css/style.css">
    <title>Каталог товаров</title>
    <style>
        body {
            background-color: #f0f8ff;
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

        .filters {
            background-color: #f0f8ff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filter label {
            font-weight: bold;
            color: #1e90ff;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }

        .product-card {
            border: 1px solid #1e90ff;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            background-color: #fff;
            position: relative;
            cursor: pointer;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;

        }
        .product-card .add-to-cart {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            border-radius: 5px;
        }
        .product-card:hover .add-to-cart {
            display: block;
        }

        .product-card h3 {
            color: #1e90ff;
        }

        .product-card p {
            margin-bottom: 5px;
        }
        .filters {
            display: flex;
            justify-content: flex-end;
        }

        .filter {
            margin-left: 10px;
        }
        .add-to-cart-popup {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px 20px;
            background-color: #f1f1f1;
            cursor: pointer;
        }

    </style>
</head>
<body>
<div class="navbar">
    <div class="navbar-right">
        <?php
        session_start();
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
<header>
    <div class="logo">

    </div>
    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
        </ul>
    </nav>
</header>

<section class="filters">
    <div class="filter">
        <label for="search">Поиск:</label>
        <input type="text" id="search" oninput="applyFilters()">
        <label for="category">Категория:</label>
        <select id="category" onchange="applyFilters()">
            <option value="all">Все</option>
            <option value="Холодильники">Холодильники</option>
            <option value="Телевизоры">Телевизоры</option>
            <option value="Пылесосы">Пылесосы</option>
            <option value="Микроволновые печи">Микроволновые печи</option>
            <option value="Стиральные машины">Стиральные машины</option>
            <option value="Кофемашины">Кофемашины</option>
            <option value="Утюги">Утюги</option>
            <option value="Мультиварки">Мультиварки</option>
            <option value="Фены">Фены</option>
            <option value="Плиты">Плиты</option>
        </select>
    </div>
    <div class="filter">
        <label for="price">Цена:</label>
        <select id="price" onchange="applyFilters()">
            <option value="all">Все</option>
            <option value="low">От дешевых к дорогим</option>
            <option value="high">От дорогих к дешевым</option>
        </select>
    </div>
    <div class="filter">
        <label for="manufacturer">Фирма:</label>
        <select id="manufacturer" onchange="applyFilters()">
            <option value="all">Все</option>
            <option value="Samsung">Samsung</option>
            <option value="LG">LG</option>
            <option value="Philips">Philips</option>
            <option value="Panasonic">Panasonic</option>
            <option value="Bosch">Bosch</option>
            <option value="DeLonghi">DeLonghi</option>
            <option value="Tefal">Tefal</option>
            <option value="Braun">Braun</option>
            <option value="Redmond">Redmond</option>
            <option value="Electrolux">Electrolux</option>
        </select>
    </div>
</section>



<section class="product-list">
    <?php
    $user_id = $_SESSION['user_id'];
    $_SESSION['user_id'] = $user_id;
    // Подключение к базе данных MySQL
    $servername = "localhost";
    $username = "vitalix";
    $password = "1234";
    $dbname = "vitalix";

    try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Ошибка подключения: " . $conn->connect_error);
    }

    // Получение данных о товарах из представления с применением фильтров и сортировки
    $category = isset($_GET['category']) ? $_GET['category'] : 'all';
    $price = isset($_GET['price']) ? $_GET['price'] : 'all';
    $manufacturer = isset($_GET['manufacturer']) ? $_GET['manufacturer'] : 'all';

    $sql = "SELECT * FROM product";

    if ($category !== 'all') {
        $sql .= " AND category = '$category'";
    }



    if ($manufacturer !== 'all') {
        $sql .= " AND brand = '$manufacturer'";
    }

    if ($price === 'low') {
        $sql .= " ORDER BY price ASC";
    } elseif ($price === 'high') {
        $sql .= " ORDER BY price DESC";
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row['id'];
            $product_name = $row['name'];
            $brand = $row['brand'];
            $category = $row['category'];
            $price = $row['price'];
            $photo_url = $row['photo'];
            echo '<div class="product-card"  data-category="' . $category . '" data-price="' . $price . '" data-manufacturer="' . $brand . '" data-product-id="' . $product_id . '">';
            echo '<img src="' . $photo_url . '" alt="Изображение товара">';
            echo '<h3>' . $product_name . '</h3>';
            echo '<p>Фирма: ' . $brand . '</p>';
            echo '<p>Цена: ' . $price . '</p>';
            echo "<a href='javascript:addToCart($product_id)'>Добавить в корзину</a>";
            echo "<p>Категория: " . $category . "</p>";
            echo '</div>';






        }
    } else {
        echo "Нет доступных товаров.";
    }

    $conn->close();
    } catch (Exception $e) {
        die('Произошла ошибка: ' . $e->getMessage());
    }

    ?>

<script>

    function addToCart(product_id) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_to_cart.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert('Товар успешно добавлен в корзину!');
            }
        };

        // Добавляем параметр user_id в запрос AJAX
        xhr.send('product_id=' + product_id + '&user_id=' + <?php echo $user_id; ?>);

        var addToCartPopup = document.createElement('div');
        addToCartPopup.className = 'add-to-cart-popup';
        addToCartPopup.innerHTML = 'Товар успешно добавлен в корзину!';
        document.body.appendChild(addToCartPopup);

        // Через некоторое время удаляем всплывающее окно
        setTimeout(function() {
            addToCartPopup.parentNode.removeChild(addToCartPopup);
        }, 2000);
    }

    function applyFilters() {
        var category = document.getElementById("category").value;
        var price = document.getElementById("price").value;
        var manufacturer = document.getElementById("manufacturer").value;
        var searchQuery = document.getElementById("search").value.toLowerCase(); // Получаем значение строки поиска и приводим к нижнему регистру

        var productList = document.querySelector(".product-list");
        var products = Array.from(productList.getElementsByClassName("product-card"));

        // Фильтрация товаров по категории
        if (category !== "all") {
            products = products.filter(function (product) {
                var productCategoryValue = product.getAttribute("data-category");
                return productCategoryValue === category;
            });
        }

        // Сортировка товаров по цене
        if (price === "low") {
            products.sort(function (a, b) {
                var priceA = parseFloat(a.getAttribute("data-price"));
                var priceB = parseFloat(b.getAttribute("data-price"));
                return priceA - priceB;
            });
        } else if (price === "high") {
            products.sort(function (a, b) {
                var priceA = parseFloat(a.getAttribute("data-price"));
                var priceB = parseFloat(b.getAttribute("data-price"));
                return priceB - priceA;
            });
        }

        // Фильтрация товаров по производителю
        if (manufacturer !== "all") {
            products = products.filter(function (product) {
                return product.getAttribute("data-manufacturer") === manufacturer;
            });
        }

        // Фильтрация товаров по строке поиска (по названию)
        products = products.filter(function (product) {
            var productName = product.getElementsByTagName("h3")[0].textContent.toLowerCase();

            return productName.includes(searchQuery);
        });


        // Очистка списка товаров
        productList.innerHTML = "";

        // Вставка отфильтрованных и отсортированных товаров
        products.forEach(function (product) {
            productList.appendChild(product);
        });
    }


</script>



