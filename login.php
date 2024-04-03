<?php
session_start();

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

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подготовленный запрос для получения данных пользователя по email
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Пользователь аутентифицирован, сохраняем его данные в сессию
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // Перенаправляем на главную страницу
    } else {
        $error = "Неверный email или пароль";
    }

    // Закрываем запрос
    $stmt->close();
    // Закрываем соединение с базой данных
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Логин</title>
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


            echo "<li><a href='register.php'>Регистрация</a></li>";
            echo "<li><a href='index.php'>Главная</a></li>";
        
        ?>
    </ul>
</nav>
    <h2>Логин</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br>
        <label for="password">Пароль:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Войти">
    </form>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</body>
</html>
