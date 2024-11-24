<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Починаємо сесію лише якщо вона ще не була розпочата
}

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}
?>
