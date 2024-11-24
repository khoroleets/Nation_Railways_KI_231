<?php
session_start();

// Перевіряємо, чи користувач авторизований
if (isset($_SESSION['user_id'])) {
    // Зберігаємо поточну сторінку для наступного перенаправлення
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];

    // Вихід з системи
    session_unset();
    session_destroy();
    header("Location: login.php"); // Перенаправлення на сторінку входу
    exit;
} else {
    header("Location: login.php"); // Якщо користувач не авторизований, редірект на login.php
    exit;
}
?>
