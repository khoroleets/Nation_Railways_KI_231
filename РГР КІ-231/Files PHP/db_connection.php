<?php
$host = '127.0.0.1';
$dbname = 'nation_railways';
$username = 'root';
$password = '';

// Ініціалізуємо змінні для статусу підключення
$connectionSuccess = false;
$connectionError = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Якщо підключення успішне, змінюємо статус
    $connectionSuccess = true;

    // Перевірка, чи поточний файл не є main.php
    if (basename($_SERVER['PHP_SELF']) !== 'main.php') {
        echo "Підключення до бази даних успішне!";
    }
} catch (PDOException $e) {
    // Якщо є помилка, зберігаємо її повідомлення у змінну
    $connectionError = "Помилка підключення: " . $e->getMessage();

    // Виводимо повідомлення про помилку, якщо файл не є main.php
    if (basename($_SERVER['PHP_SELF']) !== 'main.php') {
        echo $connectionError;
    }
}
?>
