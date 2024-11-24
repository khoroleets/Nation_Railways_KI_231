<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    try {
        // Видалення водія
        $query = "DELETE FROM driver WHERE driver_id = :driver_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['driver_id' => $driver_id]);
        $success_message = "Водія видалено успішно!";
    } catch (PDOException $e) {
        $error_message = "Помилка: " . $e->getMessage();
    }
}

// Отримати список водіїв для вибору
try {
    $query = "SELECT driver_id, name FROM driver"; // Отримуємо ID та ім'я водіїв
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Помилка: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити водія</title>
</head>
<body>
    <h1>Видалити водія</h1>

    <!-- Повідомлення про успіх або помилку -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php elseif (isset($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <!-- Форма вибору водія для видалення -->
    <form method="POST">
        <label for="driver_id">Оберіть водія:</label>
        <select name="driver_id" required>
            <?php foreach ($drivers as $driver): ?>
                <option value="<?php echo $driver['driver_id']; ?>"><?php echo htmlspecialchars($driver['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Видалити">
    </form>

    <!-- Кнопка для повернення на головну сторінку -->
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
