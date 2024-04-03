<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .product {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
        }
        .product_rec {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product_rec img {
            max-width: 30%;
            height: auto;
        }
        .product a {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .product_rec a {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        nav {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }
        nav li {
            display: inline;
            margin-right: 10px;
        }
        nav li a {
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
        nav li a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <!-- Панель навигации -->
    <nav>
    <ul>
        <?php
        session_start();
        // Проверяем, залогинен ли пользователь
        if (isset($_SESSION['user_id'])) {
            // Если пользователь залогинен, выводим ссылки на профиль, выход и корзину
            echo "<li><a href='profile.php'>Профиль</a></li>";
            echo "<li><a href='cart.php'>Корзина</a></li>";
            echo "<li><a href='logout.php'>Выход</a></li>";

        } else {
            // Если пользователь не залогинен, выводим ссылки на регистрацию и вход
            echo "<li><a href='register.php'>Регистрация</a></li>";
            echo "<li><a href='login.php'>Вход</a></li>";
        }
        ?>
    </ul>
</nav>
    <h1>Главная страница</h1>
    <h2>Фильтры:</h2>
    <form class="filter-form" method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="search">Поиск:</label>
        <input type="text" id="search" name="search">
        <label for="category">Категория:</label>
        <select id="category" name="category">
            <option value="">Выберите категорию</option>
            <?php
            session_start(); // Начинаем сессию (если еще не начата)
            // Подключение к базе данных
            $servername = "localhost";
            $username = "root"; // Ваше имя пользователя для доступа к MySQL
            $password = ""; // Ваш пароль для доступа к MySQL
            $dbname = "ecommerce_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Проверка соединения
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Получение списка уникальных категорий из базы данных
            $sql = "SELECT DISTINCT category FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['category']}'>{$row['category']}</option>";
                }
            }

            // Закрываем соединение с базой данных
            $conn->close();
            ?>
        </select>
        <label for="brand">Бренд:</label>
        <select id="brand" name="brand">
            <option value="">Выберите бренд</option>
            <?php
            // Подключение к базе данных
            $servername = "localhost";
            $username = "root"; // Ваше имя пользователя для доступа к MySQL
            $password = ""; // Ваш пароль для доступа к MySQL
            $dbname = "ecommerce_db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Проверка соединения
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Получение списка уникальных брендов из базы данных
            $sql = "SELECT DISTINCT brand FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['brand']}'>{$row['brand']}</option>";
                }
            }

            // Закрываем соединение с базой данных
            $conn->close();
            
            ?>
        </select>
        <input type="submit" value="Применить">
        <input type="button" value="Сбросить фильтры" onclick="resetFilters()">
    </form>
    <h2>Рекомендации:</h2>
    <div class="grid-container">
        <?php
        // Подключение к базе данных
        $servername = "localhost";
        $username = "root"; // Ваше имя пользователя для доступа к MySQL
        $password = ""; // Ваш пароль для доступа к MySQL
        $dbname = "ecommerce_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Проверка соединения
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Получение id пользователя из сессии
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

        // Получение предпочтительной категории пользователя, если он авторизован
        $user_category = '';
        if (!empty($user_id)) {
            $sql = "SELECT preferred_category FROM users WHERE id = '$user_id'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_category = $row['preferred_category'];
            }
        }

        // Формируем SQL запрос на основе категории предпочтений пользователя
        $recommendation_sql = "SELECT * FROM products WHERE category = '$user_category'";

        // Получение товаров из базы данных на основе запроса
        $recommendation_result = $conn->query($recommendation_sql);

        if ($recommendation_result->num_rows > 0) {
            // Выводим данные каждого товара
            while($row = $recommendation_result->fetch_assoc()) {
                echo "<div class='product_rec'>";
                echo "<a href='product.php?id={$row['id']}'>";
                echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                echo "<p><strong>{$row['name']}</strong></p>";
                echo "<p><strong>Категория:</strong> {$row['category']}</p>";
                echo "<p><strong>Бренд:</strong> {$row['brand']}</p>";
                echo "<p><strong>Цена:</strong> {$row['price']} руб.</p>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "Нет товаров для отображения";
        }

        // Закрываем соединение с базой данных
        $conn->close();
        ?>
    </div>
    <h2>Товары:</h2>
    <div class="grid-container">
        <?php
        // Подключение к базе данных
        $servername = "localhost";
        $username = "root"; // Ваше имя пользователя для доступа к MySQL
        $password = ""; // Ваш пароль для доступа к MySQL
        $dbname = "ecommerce_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Проверка соединения
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Формируем SQL запрос на основе критериев фильтрации и поиска
        $sql = "SELECT products.id, products.name, products.category, products.brand, products.price, products.image, AVG(reviews.rating) AS average_rating FROM products LEFT JOIN reviews ON products.id = reviews.product_id";
        
        $where = [];

        // Условие поиска
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $where[] = "(products.name LIKE '%$search%' OR products.category LIKE '%$search%' OR products.brand LIKE '%$search%')";
        }

        // Условие категории
        if(isset($_GET['category']) && !empty($_GET['category'])) {
            $category = $_GET['category'];
            $where[] = "products.category='$category'";
        }

        // Условие бренда
        if(isset($_GET['brand']) && !empty($_GET['brand'])) {
            $brand = $_GET['brand'];
            $where[] = "products.brand='$brand'";
        }

        // Добавляем условия в запрос
        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " GROUP BY products.id";

        // Получение товаров из базы данных на основе запроса
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Выводим данные каждого товара
            while($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<a href='product.php?id={$row['id']}'>";
                echo "<img src='{$row['image']}' alt='{$row['name']}'>";
                echo "<p><strong>{$row['name']}</strong></p>";
                echo "<p><strong>Категория:</strong> {$row['category']}</p>";
                echo "<p><strong>Бренд:</strong> {$row['brand']}</p>";
                echo "<p><strong>Цена:</strong> {$row['price']} руб.</p>";
                echo "<p><strong>Средняя оценка:</strong> ";
                echo number_format($row['average_rating'], 1);
                echo "</p>";
                // Проверяем, залогинен ли пользователь
                if (isset($_SESSION['user_id'])) {
                    // Если пользователь залогинен, выводим кнопку "Добавить в корзину"
                    echo "<form method='post' action='add_to_cart.php'>";
                    echo "<input type='hidden' name='product_id' value='{$row['id']}'>";
                    echo "<input type='submit' value='Добавить в корзину'>";
                    echo "</form>";
                }
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "Нет товаров для отображения";
        }

        // Закрываем соединение с базой данных
        $conn->close();
        ?>
    </div>

    <script>
        function resetFilters() {
            document.getElementById("search").value = "";
            document.getElementById("category").selectedIndex = 0;
            document.getElementById("brand").selectedIndex = 0;
        }
    </script>
</body>
</html>
