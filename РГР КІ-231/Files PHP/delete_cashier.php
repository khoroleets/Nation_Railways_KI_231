<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cashier_id'])) {
    $cashier_id = $_POST['cashier_id'];

    try {
        // Видалення касира
        $query = "DELETE FROM cashier WHERE cashier_id = :cashier_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['cashier_id' => $cashier_id]);

        echo "Касира видалено успішно!";
    } catch (PDOException $e) {
        echo "Помилка під час видалення касира: " . $e->getMessage();
    }
}

try {
    // Отримати список касирів для вибору
    $query = "SELECT cashier_id, name FROM cashier";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $cashiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити касира</title>
</head>
<body>
    <h1>Видалити касира</h1>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <form method="POST">
        <label for="cashier_id">Оберіть касира:</label>
        <select name="cashier_id" required>
            <?php foreach ($cashiers as $cashier): ?>
                <option value="<?php echo $cashier['cashier_id']; ?>"><?php echo htmlspecialchars($cashier['name']); ?></option>
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
