<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Отримуємо дані клієнтів
    $customer_query = "SELECT name, date_of_birth, phone_number FROM customer";
    if (isset($_POST['filter']) && !empty($_POST['phone_number'])) {
        $phone_number = $_POST['phone_number'];
        $customer_query .= " WHERE phone_number LIKE ?";
        $customer_stmt = $pdo->prepare($customer_query);
        $customer_stmt->execute(["%$phone_number%"]);
    } else {
        $customer_stmt = $pdo->query($customer_query);
    }
    
    $customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список клієнтів</title>
</head>
<body>
    <h1>Список клієнтів</h1>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <!-- Таблиця клієнтів -->
    <table border="1">
        <tr>
            <th>Ім'я</th>
            <th>Дата народження</th>
            <th>Номер телефону</th>
        </tr>
        <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                <td><?php echo htmlspecialchars($customer['date_of_birth']); ?></td>
                <td><?php echo htmlspecialchars($customer['phone_number']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align: center;">Клієнтів не знайдено.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Форма фільтрування клієнтів за номером телефону -->
    <h2>Фільтрувати клієнтів</h2>
    <form method="post">
        <input type="text" name="phone_number" placeholder="Фільтр за номером телефону">
        <button type="submit" name="filter">Фільтрувати</button>
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
