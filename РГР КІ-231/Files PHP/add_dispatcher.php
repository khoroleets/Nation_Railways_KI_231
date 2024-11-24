<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

// Якщо запит на вихід, закриваємо сесію
if (isset($_POST['logout'])) {
    session_destroy(); // Знищуємо сесію
    header("Location: login.php"); // Перенаправляємо на сторінку входу після виходу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $salary = $_POST['salary'];

    // Фільтрація імені (лише букви і пробіли)
    if (!preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄ' ]+$/u", $name)) {
        echo "Помилка: Некоректне ім'я. Ім'я повинно містити лише літери та пробіли.";
        exit;
    }

    // Фільтрація зарплати (лише числа, не менше ніж 0)
    if (!preg_match("/^\d+(\.\d{1,2})?$/", $salary) || $salary < 0) {
        echo "Помилка: Некоректна зарплата. Введіть позитивну суму, до двох десяткових знаків.";
        exit;
    }

    // Отримуємо найбільший dispatcher_id
    $query = "SELECT MAX(dispatcher_id) AS max_id FROM dispatcher";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Визначаємо наступний dispatcher_id
    $next_dispatcher_id = ($result['max_id'] !== null) ? $result['max_id'] + 1 : 1;

    // Вставка нового диспетчера з наступним dispatcher_id
    $insert_query = "INSERT INTO dispatcher (dispatcher_id, name, salary) VALUES (:dispatcher_id, :name, :salary)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->execute([
        'dispatcher_id' => $next_dispatcher_id,
        'name' => $name,
        'salary' => $salary
    ]);

    echo "Диспетчера додано успішно!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати диспетчера</title>
</head>
<body>
    <h1>Додати нового диспетчера</h1>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="">
        <input type="submit" name="logout" value="Вийти з системи">
    </form>

    <form method="POST">
        <label for="name">Ім'я:</label>
        <input type="text" name="name" placeholder="Ваше ім'я" required>
        <br>
        <label for="salary">Зарплата:</label>
        <input type="number" name="salary" placeholder="Ваша зарплата" required step="0.01">
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
