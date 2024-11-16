<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання списку квитків
$query = "SELECT ticket_id, customer_id, train_id, ticket_type, cashier_id, price FROM ticket";
$stmt = $pdo->prepare($query);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];
    $customer_id = $_POST['customer_id'] ?? '';
    $train_id = $_POST['train_id'] ?? '';
    $ticket_type = $_POST['ticket_type'] ?? '';
    $cashier_id = $_POST['cashier_id'] ?? '';
    $price = $_POST['price'] ?? '';

    // Оновлення даних квитка, якщо всі поля заповнені
    if ($customer_id && $train_id && $ticket_type && $cashier_id && $price !== '') {
        $query = "UPDATE ticket SET customer_id = :customer_id, train_id = :train_id, ticket_type = :ticket_type, 
                  cashier_id = :cashier_id, price = :price WHERE ticket_id = :ticket_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'customer_id' => $customer_id,
            'train_id' => $train_id,
            'ticket_type' => $ticket_type,
            'cashier_id' => $cashier_id,
            'price' => $price,
            'ticket_id' => $ticket_id
        ]);

        echo "Дані квитка оновлено успішно!";
    } else {
        echo "Будь ласка, заповніть усі поля.";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити квиток</title>
</head>
<body>
    <h1>Оновити квиток</h1>
    <form method="POST">
        <label for="ticket_id">Оберіть квиток за ID:</label>
        <select name="ticket_id" required>
            <?php foreach ($tickets as $ticket): ?>
                <option value="<?php echo $ticket['ticket_id']; ?>"><?php echo htmlspecialchars($ticket['ticket_id']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="customer_id">ID Клієнта:</label>
        <input type="number" name="customer_id" required>
        <br>
        <label for="train_id">ID Поїзда:</label>
        <input type="number" name="train_id" required>
        <br>

        <label for="ticket_type">Тип квитка:</label>
        <input type="text" name="ticket_type" pattern="[A-Za-zА-Яа-яЁёіІєЄїЇіґҐ]+" title="Тип квитка має містити лише букви" required>
        <br>

        <label for="cashier_id">ID Касира:</label>
        <input type="number" name="cashier_id" required>
        <br>
        <label for="price">Ціна:</label>
        <input type="number" name="price" required>
        <br>
        <input type="submit" value="Оновити">
    </form>
    <br>
    
    <form method="GET">
        <input type="hidden" name="show_tickets" value="1">
        <input type="submit" value="Показати наявні квитки">
    </form>
    
    <?php if (isset($_GET['show_tickets'])): ?>
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

