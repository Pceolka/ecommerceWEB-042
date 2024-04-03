<?php
session_start(); // Начинаем или возобновляем сессию

// Проверяем, вошел ли пользователь в систему
if (isset($_SESSION['user_id'])) {
    $user_name = $_SESSION['username']; // Получаем имя пользователя из сессии
    $user_id = $_SESSION['user_id']; // Получаем ID пользователя из сессии
} else {
    // Если пользователь не вошел в систему, перенаправляем его на страницу входа
    header("Location: login.php");
    exit; // Прекращаем выполнение скрипта
}

// Обработка отправленной формы для обновления адреса доставки и платежных предпочтений
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Обработка никнейма
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
            $sql_username = "UPDATE users SET username='$username' WHERE id=$user_id";
            if ($conn->query($sql_username) === TRUE) {
                $success_message = "username успешно обновлен!";
            } else {
                $error_message = "Ошибка при обновлении username: " . $conn->error;
            }
        }

    // Обработка адреса доставки
    if (!empty($_POST['shipping_address'])) {
        $shipping_address = $_POST['shipping_address'];
        $sql_shipping = "UPDATE users SET shipping_address='$shipping_address' WHERE id=$user_id";
        if ($conn->query($sql_shipping) === TRUE) {
            $success_message = "Адрес доставки успешно обновлен!";
        } else {
            $error_message = "Ошибка при обновлении адреса доставки: " . $conn->error;
        }
    }

    // Обработка платежных предпочтений
    if (!empty($_POST['payment_preferences'])) {
        $payment_preferences = $_POST['payment_preferences'];
        $sql_payment = "UPDATE users SET payment_preferences='$payment_preferences' WHERE id=$user_id";
        if ($conn->query($sql_payment) === TRUE) {
            $success_message = "Платежные предпочтения успешно обновлены!";
        } else {
            $error_message = "Ошибка при обновлении платежных предпочтений: " . $conn->error;
        }
    }

    // Закрываем соединение с базой данных
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя - <?php echo $user_name; ?></title>
    <!-- Остальные стили и скрипты -->
    <style>
        .edit-form {
            display: none; /* По умолчанию форма скрыта */
        }
    </style>
    <script>
        function toggleEditForm() {
            var editForm = document.getElementById('edit-form');
            if (editForm.style.display === 'none') {
                editForm.style.display = 'block';
            } else {
                editForm.style.display = 'none';
            }
        }
    </script>
    
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
<nav>
    <ul>
        <?php
        // Проверяем, залогинен ли пользователь
        if (isset($_SESSION['user_id'])) {
            // Получаем идентификатор пользователя из сессии
            $user_id = $_SESSION['user_id'];

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

            // Запрос для получения статуса администратора пользователя
            $sql = "SELECT is_admin FROM users WHERE id = $user_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Проверяем, является ли пользователь администратором
                if ($row['is_admin'] == 1) {
                    echo "<li><a href='admin_panel.php'>Админ панель</a></li>";
                }
            }

            // Закрываем соединение с базой данных
            $conn->close();

            // Выводим ссылки на главную, корзину и выход
            echo "<li><a href='index.php'>Главная</a></li>";
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


    <!-- Содержимое страницы профиля -->
    <h1>Профиль пользователя: <?php echo $user_name; ?></h1>

    <!-- Кнопка для отображения формы изменений -->
    <button onclick="toggleEditForm()">Изменить данные</button>

    <!-- Форма для ввода изменений -->
    <form id="edit-form" class="edit-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Имя пользователя:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $user_name; ?>"><br><br>

        <label for="shipping_address">Адрес доставки:</label><br>
        <textarea id="shipping_address" name="shipping_address"><?php echo isset($_POST['shipping_address']) ? $_POST['shipping_address'] : ''; ?></textarea><br><br>
        
        <label for="payment_preferences">Платежные предпочтения:</label><br>
        <textarea id="payment_preferences" name="payment_preferences"><?php echo isset($_POST['payment_preferences']) ? $_POST['payment_preferences'] : ''; ?></textarea><br><br>

        <input type="submit" value="Сохранить изменения">
    </form>

    <!-- Отображение сообщений об успешном или неудачном обновлении -->
    <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

</body>
</html>
