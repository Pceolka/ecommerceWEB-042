<?php
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Проверка на администратора
$user_id = $_SESSION['user_id'];
$sql = "SELECT is_admin FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['is_admin'] != 1) {
        echo "Вы не администратор.";
        exit();
    }
} else {
    echo "Ошибка при проверке администратора.";
    exit();
}

// Обработка добавления товара
if (isset($_POST["add_product"])) {
    $name = $_POST['add_name'];
    $category = $_POST['add_category'];
    $brand = $_POST['add_brand'];
    $description = $_POST['add_description'];
    $price = $_POST['add_price'];
    $image = $_POST['add_image'];

    // Подготовленный запрос для вставки данных в таблицу
    $stmt = $conn->prepare("INSERT INTO products (name, category, brand, description, price, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssds", $name, $category, $brand, $description, $price, $image);

    // Выполняем запрос
    if ($stmt->execute()) {
        $success_message = "Товар успешно добавлен!";
    } else {
        $error_message = "Ошибка при добавлении товара: " . $conn->error;
    }

    // Закрываем запрос
    $stmt->close();
}

// Обработка редактирования товара
if (isset($_POST["edit_product"])) {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $category = $_POST['edit_category'];
    $brand = $_POST['edit_brand'];
    $description = $_POST['edit_description'];
    $price = $_POST['edit_price'];
    $image = $_POST['edit_image'];

    // Подготовленный запрос для обновления данных в таблице
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, brand=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("ssssdsi", $name, $category, $brand, $description, $price, $image, $id);

    // Выполняем запрос
    if ($stmt->execute()) {
        $success_message = "Товар успешно отредактирован!";
    } else {
        $error_message = "Ошибка при редактировании товара: " . $conn->error;
    }

    // Закрываем запрос
    $stmt->close();
}

// Обработка удаления товара
if (isset($_POST["delete_product"])) {
    $id = $_POST['delete_id'];

    // Подготовленный запрос для удаления данных из таблицы
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);

    // Выполняем запрос
    if ($stmt->execute()) {
        $success_message = "Товар успешно удален!";
    } else {
        $error_message = "Ошибка при удалении товара: " . $conn->error;
    }

    // Закрываем запрос
    $stmt->close();
}

// Закрываем соединение с базой данных
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>
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
    <h1>Админ панель</h1>
    
    <!-- Форма добавления товара -->
    <h2>Добавить товар</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- Поля для добавления товара -->
        <label for="add_name">Название:</label><br>
        <input type="text" id="add_name" name="add_name" required><br>
        <label for="add_category">Категория:</label><br>
        <input type="text" id="add_category" name="add_category" required><br>
        <label for="add_brand">Бренд:</label><br>
        <input type="text" id="add_brand" name="add_brand" required><br>
        <label for="add_description">Описание:</label><br>
        <textarea id="add_description" name="add_description" required></textarea><br>
        <label for="add_price">Цена:</label><br>
        <input type="number" id="add_price" name="add_price" step="0.01" min="0" required><br>
        <label for="add_image">Изображение (URL):</label><br>
        <input type="text" id="add_image" name="add_image" required><br><br>
        <input type="submit" name="add_product" value="Добавить товар">
    </form>

    <!-- Форма редактирования товара -->
    <h2>Редактировать товар</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- Поля для редактирования товара -->
        <label for="edit_id">ID товара для редактирования:</label><br>
        <input type="number" id="edit_id" name="edit_id" required><br> <!-- Поле для ввода ID товара для редактирования -->
        <label for="edit_name">Название:</label><br>
        <input type="text" id="edit_name" name="edit_name" required><br>
        <label for="edit_category">Категория:</label><br>
        <input type="text" id="edit_category" name="edit_category" required><br>
        <label for="edit_brand">Бренд:</label><br>
        <input type="text" id="edit_brand" name="edit_brand" required><br>
        <label for="edit_description">Описание:</label><br>
        <textarea id="edit_description" name="edit_description" required></textarea><br>
        <label for="edit_price">Цена:</label><br>
        <input type="number" id="edit_price" name="edit_price" step="0.01" min="0" required><br>
        <label for="edit_image">Изображение (URL):</label><br>
        <input type="text" id="edit_image" name="edit_image" required><br><br>
        <input type="submit" name="edit_product" value="Редактировать товар">
    </form>

    <!-- Форма удаления товара -->
    <h2>Удалить товар</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <!-- Поле для ввода ID товара для удаления -->
        <label for="delete_id">ID товара для удаления:</label><br>
        <input type="number" id="delete_id" name="delete_id" required><br> <!-- Используем input type="number" -->
        <input type="submit" name="delete_product" value="Удалить товар">
    </form>

    <!-- Вывод сообщений об успешном или неудачном удалении товара -->
    <?php
    if (isset($success_message)) {
        echo "<p style='color: green;'>$success_message</p>";
    }
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
</body>
</html>
