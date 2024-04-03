<?php
session_start();

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
$user_id = $_SESSION['user_id'];

// Подготовка SQL-запроса для получения данных о доставке и оплате пользователя
$sql = "SELECT shipping_address, payment_preferences FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Получение данных о доставке и оплате пользователя
    $row = $result->fetch_assoc();
    $shipping_address = $row['shipping_address'];
    $payment_method = $row['payment_preferences'];
} else {
    // Обработка случая, если данные не найдены
    echo "Данные пользователя не найдены";
    exit(); // Прекращаем выполнение скрипта
}

// Проверяем, была ли отправлена форма и метод запроса POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Получение данных из формы
    $fullname = $_POST['fullname'] ?? ''; // Если ключ отсутствует, устанавливаем значение по умолчанию
    $address = $_POST['address'] ?? ''; // Если ключ отсутствует, устанавливаем значение по умолчанию
    
    // Получение идентификаторов купленных товаров из сессии
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $product_ids = implode(",", array_keys($_SESSION['cart']));
        
        // Получение цен купленных товаров из базы данных
        $prices = [];
        $sql = "SELECT id, price, category FROM products WHERE id IN ($product_ids)";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $prices[$row['id']] = $row['price'];
                $product_category = $row['category']; // Записываем категорию товара
            }
        }
        
        // Получение общей суммы всех товаров в корзине
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $total_amount += $prices[$product_id] * $quantity;
        }
    } else {
        $product_ids = ''; // Если нет купленных товаров, оставляем пустую строку
        $total_amount = 0; // Устанавливаем ноль для общей суммы
        $product_category = ''; // Устанавливаем пустую строку для категории товара
    }

    // Подготовка SQL-запроса для добавления заказа в базу данных
    $sql = "INSERT INTO orders (user_id, product_ids, fullname, address, payment_method, total_amount) VALUES ('$user_id', '$product_ids', '$fullname', '$address', '$payment_method', '$total_amount')";

    if ($conn->query($sql) === TRUE) {
        // Заказ успешно оформлен
        echo "Заказ успешно оформлен";
        
        // Обновление предпочтительной категории пользователя на основе категории купленных товаров
        if (!empty($product_category)) {
            $sql_update = "UPDATE users SET preferred_category = '$product_category' WHERE id = $user_id";
            $conn->query($sql_update);
        }
        
        unset($_SESSION['cart']); // Удаление купленных товаров из сессии после оформления заказа
        header("refresh:3;url=index.php"); // Перенаправление на главную страницу через 3 секунды
        exit(); // Прекращаем выполнение скрипта
    } else {
        echo "Ошибка при оформлении заказа: " . $conn->error;
    }
}

// Закрываем соединение с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
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
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product a {
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
    <h1>Оформление заказа</h1>
    <form action="checkout.php" method="post">
        <label for="fullname">ФИО:</label><br>
        <input type="text" id="fullname" name="fullname" required><br><br>
        <label for="address">Адрес доставки:</label><br>
        <textarea id="address" name="address" required><?php echo $shipping_address; ?></textarea><br><br>
        <label for="payment_method">Способ оплаты:</label><br>
        <input type="text" id="payment_method" name="payment_method" value="<?php echo $payment_method; ?>" readonly><br><br>
        <input type="submit" name="submit" value="Оформить заказ">
        <input type="hidden" name="product_category" value="<?php echo $product_category; ?>">
    </form>
</body>
</html>
