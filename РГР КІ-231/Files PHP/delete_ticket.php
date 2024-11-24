<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

// Отримання списку квитків
$query = "SELECT ticket_id, customer_id, train_id, ticket_type, cashier_id, price FROM ticket";
$stmt = $pdo->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    // Видалення квитка
    $query = "DELETE FROM ticket WHERE ticket_id = :ticket_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['ticket_id' => $ticket_id]);

    $success_message = "Квиток видалено успішно!";
}

// Отримання списку квитків для відображення
$display_tickets = false;
if (isset($_GET['show_tickets'])) {
    $display_tickets = true;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити квиток</title>
</head>
<body>
    <h1>Видалити квиток</h1>

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

    <!-- Форма для видалення квитка -->
    <form method="POST">
        <label for="ticket_id">Оберіть квиток за його ID:</label>
        <select name="ticket_id" required>
            <?php foreach ($tickets as $ticket): ?>
                <option value="<?php echo $ticket['ticket_id']; ?>"><?php echo htmlspecialchars($ticket['ticket_id']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Видалити">
    </form>

    <br>

    <!-- Форма для показу наявних квитків -->
    <form method="GET">
        <input type="hidden" name="show_tickets" value="1">
        <input type="submit" value="Показати наявні квитки">
    </form>

    <?php if ($display_tickets): ?>
        <h2>Список наявних квитків</h2>
        <table border="1">
            <tr>
                <th>ID квитка</th>
                <th>ID клієнта</th>
                <th>ID потяга</th>
                <th>Тип квитка</th>
                <th>ID касира</th>
                <th>Ціна</th>
            </tr>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket['ticket_id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['customer_id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['train_id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['ticket_type']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['cashier_id']); ?></td>
                    <td><?php echo htmlspecialchars($ticket['price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <form method="GET">
            <input type="submit" value="Згорнути список">
        </form>
    <?php endif; ?>

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
