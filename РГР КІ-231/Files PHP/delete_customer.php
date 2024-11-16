<?php
include 'db_connection.php'; // Підключення до бази даних

// Код для видалення клієнта
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    
    try {
        // Спочатку видаляємо всі пов'язані записи в таблиці ticket
        $delete_tickets = $pdo->prepare("DELETE FROM ticket WHERE customer_id = ?");
        $delete_tickets->execute([$customer_id]);

        // Тепер видаляємо клієнта
        $delete_customer = $pdo->prepare("DELETE FROM customer WHERE customer_id = ?");
        $delete_customer->execute([$customer_id]);
        $success_message = "Клієнт успішно видалений!"; // Повідомлення про успіх
    } catch (PDOException $e) {
        $error_message = "Помилка: " . $e->getMessage();
    }
}

// Отримати список клієнтів для вибору
$query = "SELECT customer_id, name FROM customer"; // Отримуємо ID та ім'я клієнтів
$stmt = $pdo->prepare($query);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити клієнта</title>
</head>
<body>
    <h1>Видалити клієнта</h1>

    <!-- Повідомлення про успіх або помилку -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php elseif (isset($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="customer_id">Оберіть клієнта:</label>
        <select name="customer_id" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?php echo $customer['customer_id']; ?>"><?php echo htmlspecialchars($customer['name']); ?></option>
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
