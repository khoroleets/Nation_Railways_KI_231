<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $validUsername = 'admin';
    $validPassword = 'admin';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Перевірка логіна та пароля
    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['user_id'] = 1; // Просте значення для ідентифікації користувача

        // Перевірка, чи є в сесії попередня сторінка для перенаправлення
        if (isset($_SESSION['redirect_to'])) {
            $redirectUrl = $_SESSION['redirect_to'];
            unset($_SESSION['redirect_to']); // Видаляємо redirect_to після перенаправлення
            header("Location: $redirectUrl");
        } else {
            header("Location: main.php"); // Перенаправлення на сторінку за замовчуванням
        }
        exit;
    } else {
        echo "Невірний логін або пароль.";
    }
}
?>
