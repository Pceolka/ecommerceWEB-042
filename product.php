<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_id = $_GET['id'] ?? '';

if(empty($product_id)) {
    echo "Некорректный запрос";
    exit();
}

$product_id = mysqli_real_escape_string($conn, $product_id);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'] ?? '';
    $user_rating = $_POST['user_rating'] ?? '';
    $user_review = $_POST['user_review'] ?? '';

    if (empty($user_id) || empty($user_rating)) {
        echo "Некорректные данные для добавления отзыва.";
    } else {
        $user_id = mysqli_real_escape_string($conn, $user_id);
        $user_rating = mysqli_real_escape_string($conn, $user_rating);
        $user_review = mysqli_real_escape_string($conn, $user_review);

        $sql = "INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES ('$product_id', '$user_id', '$user_rating', '$user_review')";

        if ($conn->query($sql) === TRUE) {
            echo "Отзыв успешно добавлен.";
        } else {
            echo "Ошибка при добавлении отзыва: " . $conn->error;
        }
    }
}

$sql = "SELECT id, name, category, brand, price, image FROM products WHERE id='$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = htmlspecialchars($row['name']);
    $category = htmlspecialchars($row['category']);
    $brand = htmlspecialchars($row['brand']);
    $price = htmlspecialchars($row['price']);
    $image = htmlspecialchars($row['image']);
} else {
    echo "Товар не найден";
    exit();
}

$reviews_sql = "SELECT rating, review_text FROM reviews WHERE product_id='$product_id'";
$reviews_result = $conn->query($reviews_sql);

$reviews = array();
if ($reviews_result->num_rows > 0) {
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

$total_rating = 0;
$total_reviews = 0;

if (!empty($reviews)) {
    foreach ($reviews as $review) {
        $total_rating += $review['rating'];
        $total_reviews++;
    }
    $average_rating = $total_rating / $total_reviews;
} else {
    $average_rating = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product_name; ?></title>
    <style>
        .product-container {
            display: flex;
            align-items: flex-start;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .product-image {
            flex: 0 0 500px;
            margin-right: 20px;
        }
        .product-image img {
            width: 100%;
            height: auto;
        }
        .product-info {
            flex: 1;
        }
        .product-reviews {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
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
    <h1><?php echo $product_name; ?></h1>
    <div class="product-container">
        <div class="product-image">
            <img src="<?php echo $image; ?>" alt="<?php echo $product_name; ?>">
        </div>
        <div class="product-info">
            <p><strong>Категория:</strong> <?php echo $category; ?></p>
            <p><strong>Бренд:</strong> <?php echo $brand; ?></p>
            <p><strong>Цена:</strong> <?php echo $price; ?> руб.</p>
            <p><strong>Средняя оценка:</strong> <?php echo number_format($average_rating, 1); ?>/5 (<?php echo $total_reviews; ?> отзывов)</p>
        </div>
        <form method="post" action="product.php?id=<?php echo $product_id; ?>">
            <div class="product-reviews">
                <h2>Добавить отзыв и оценку</h2>
                <label for="user_rating">Оценка:</label>
                <select id="user_rating" name="user_rating">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <br><br>
                <label for="user_review">Отзыв:</label><br>
                <textarea id="user_review" name="user_review"></textarea><br><br>
                <input type="submit" name="submit_review" value="Отправить отзыв">
            </div>
        </form>
        <form method="post" action="add_to_cart.php">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <input type="submit" value="Добавить в корзину">
        </form>
    </div>
    <div class="product-reviews">
        <h2>Отзывы о товаре</h2>
        <?php
        if (!empty($reviews)) {
            foreach ($reviews as $review) {
                $rating = $review['rating'];
                $review_text = $review['review_text'];
                //echo "<p><strong>Оценка:</strong> $rating</p>";
                echo "<p> $review_text</p>";
            }
        } else {
            echo "<p>Пока нет отзывов.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
