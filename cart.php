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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    // Удаление товара из корзины
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        // Сохранение идентификаторов оставшихся товаров в сессии
        $remaining_products = array_keys($_SESSION['cart']);
        $_SESSION['remaining_products'] = $remaining_products;
        echo json_encode(array("success" => true));
        exit();
    } else {
        echo json_encode(array("success" => false, "message" => "Товар не найден в корзине"));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        
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
            echo "<li><a href='index.php'>Главная</a></li>";
            echo "<li><a href='profile.php'>Профиль</a></li>";
            echo "<li><a href='logout.php'>Выход</a></li>";
        } else {
            // Если пользователь не залогинен, выводим ссылки на регистрацию и вход
            echo "<li><a href='register.php'>Регистрация</a></li>";
            echo "<li><a href='login.php'>Вход</a></li>";
        }
        ?>
    </ul>
</nav>
    <h1>Корзина</h1>
    <table>
        <tr>
            <th>Название товара</th>
            <th>Цена</th>
            <th>Действия</th>
        </tr>
        <?php
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                // Получаем информацию о товаре из базы данных
                $sql = "SELECT name, price FROM products WHERE id='$product_id'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $product_name = $row['name'];
                    $product_price = $row['price'];
                    ?>
                    <tr>
                        <td><?php echo $product_name; ?></td>
                        <td><?php echo $product_price; ?> руб.</td>
                        <td>
                            <form class="remove-form" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <button type="submit" class="remove-btn">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td colspan="3">
                    <form action="checkout.php" method="post">
                        <button type="submit">Оформить заказ</button>
                    </form>
                </td>
            </tr>
            <?php
        } else {
            echo "<tr><td colspan='3'>Корзина пуста</td></tr>";
        }
        ?>
    </table>

    <script>
        // Обработчик нажатия кнопки удаления
        document.querySelectorAll('.remove-btn').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault();
                let form = this.closest('.remove-form');
                let productId = form.querySelector('input[name="product_id"]').value;

                // Отправка AJAX-запроса для удаления товара из корзины
                fetch('cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'product_id=' + encodeURIComponent(productId),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Успешно удален товар из корзины, перезагрузим страницу
                        location.reload();
                    } else {
                        alert('Ошибка: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                });
            });
        });
    </script>
</body>
</html>

<?php
// Закрываем соединение с базой данных
$conn->close();
?>
