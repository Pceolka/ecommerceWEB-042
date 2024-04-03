<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, был ли передан идентификатор товара
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        // Добавляем товар в корзину пользователя (в сессию)
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array(); // Инициализируем корзину, если она еще не создана
        }
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = 1; // Добавляем товар в корзину с количеством 1, если его там еще нет
        } else {
            $_SESSION['cart'][$product_id]++; // Увеличиваем количество товара, если он уже есть в корзине
        }

        // Перенаправляем пользователя обратно на страницу товара
        header("Location: product.php?id=$product_id");
        exit();
    }
}

// Если идентификатор товара не был передан или возникла ошибка, перенаправляем пользователя на главную страницу
header("Location: index.php");
exit();
?>
