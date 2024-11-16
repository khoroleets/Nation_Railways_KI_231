<?php
include 'db_connection.php'; // Підключення до бази даних

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Отримуємо дані квитків
    $ticket_query = "SELECT customer_id, train_id, ticket_type, cashier_id, price FROM ticket";
    $ticket_stmt = $pdo->prepare($ticket_query);
    $ticket_stmt->execute();
    $tickets = $ticket_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список квитків</title>
</head>
<body>
    <h1>Список квитків</h1>
    <table border="1">
        <tr>
            <th>ID Клієнта</th>
            <th>ID Поїзда</th>
            <th>Тип квитка</th>
            <th>ID Касира</th>
            <th>Ціна</th>
        </tr>
        <?php if (!empty($tickets)): ?>
            <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?php echo htmlspecialchars($ticket['customer_id']); ?></td>
                <td><?php echo htmlspecialchars($ticket['train_id']); ?></td>
                <td><?php echo htmlspecialchars($ticket['ticket_type']); ?></td>
                <td><?php echo htmlspecialchars($ticket['cashier_id']); ?></td>
                <td><?php echo htmlspecialchars($ticket['price']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align: center;">Квитків не знайдено.</td>
            </tr>
        <?php endif; ?>
    </table>

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
