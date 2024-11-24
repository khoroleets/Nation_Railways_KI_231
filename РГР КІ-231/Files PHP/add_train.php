<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];

    // Валідація: тип потяга може містити лише літери
    if (empty($type) || !preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄёЁ\s]+$/u", $type)) {
        echo "Помилка: Тип потяга може містити тільки літери.";
        exit;
    }

    // Отримання наступного ID потяга
    $query = "SELECT MAX(train_id) AS max_id FROM train";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $max_id = $stmt->fetchColumn();
    $train_id = $max_id ? $max_id + 1 : 1; 

    // Додавання нового потяга
    $query = "INSERT INTO train (train_id, type) VALUES (:train_id, :type)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['train_id' => $train_id, 'type' => $type]);

    echo "Потяг додано успішно! (ID: $train_id)";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати потяг</title>
</head>
<body>
    <h1>Додати потяг</h1>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <form method="POST">
        <label for="type">Тип потяга:</label>
        <input type="text" name="type" required>
        <br>
        <input type="submit" value="Додати">
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="http://localhost/nation_railways/main.php" style="
            display: inline-block;
            padding: 5px 10px;
            border: 2px solid black;
            color: black;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            border-radius: 5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;">
            Повернутись до головної сторінки
        </a>
    </div>
</body>
</html>
