<?php
session_start(); // Начинаем или возобновляем сессию

// Удаляем все данные сессии
session_unset();

// Уничтожаем сессию
session_destroy();

// Перенаправляем пользователя на страницу входа или любую другую страницу
header("Location: login.php");
exit; // Прекращаем выполнение скрипта
?>
