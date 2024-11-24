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
    $customer_id = $_POST['customer_id'];
    $train_id = $_POST['train_id'];
    $ticket_type = $_POST['ticket_type'];
    $cashier_id = $_POST['cashier_id'];
    $price = $_POST['price'];

    // Валідація ID клієнта, поїзда і касира (цілі числа і більше 0)
    if (!filter_var($customer_id, FILTER_VALIDATE_INT) || $customer_id <= 0) {
        echo "Помилка: Некоректний ID клієнта.";
        exit;
    }

    if (!filter_var($train_id, FILTER_VALIDATE_INT) || $train_id <= 0) {
        echo "Помилка: Некоректний ID поїзда.";
        exit;
    }

    if (!filter_var($cashier_id, FILTER_VALIDATE_INT) || $cashier_id <= 0) {
        echo "Помилка: Некоректний ID касира.";
        exit;
    }

    // Валідація типу квитка (лише літери)
    if (empty($ticket_type) || !preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄёЁ\s]+$/u", $ticket_type)) {
        echo "Помилка: Тип квитка може містити тільки літери.";
        exit;
    }

    // Валідація ціни (ціле число більше або рівне 0)
    if (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0) {
        echo "Помилка: Некоректна ціна.";
        exit;
    }

    // Отримуємо найбільший ticket_id
    $query = "SELECT MAX(ticket_id) AS max_id FROM ticket";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Визначаємо наступний ticket_id
    $next_ticket_id = ($result['max_id'] !== null) ? $result['max_id'] + 1 : 1;

    // Вставка нового квитка з наступним ticket_id
    $insert_query = "INSERT INTO ticket (ticket_id, customer_id, train_id, ticket_type, cashier_id, price) 
                     VALUES (:ticket_id, :customer_id, :train_id, :ticket_type, :cashier_id, :price)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->execute([
        'ticket_id' => $next_ticket_id,
        'customer_id' => $customer_id,
        'train_id' => $train_id,
        'ticket_type' => $ticket_type,
        'cashier_id' => $cashier_id,
        'price' => $price
    ]);

    echo "Квиток додано успішно!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати квиток</title>
</head>
<body>
    <h1>Додати новий квиток</h1>

    <!-- Кнопка для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <form method="POST">
        <label for="customer_id">ID Клієнта:</label>
        <input type="number" name="customer_id" required>
        <br>
        <label for="train_id">ID Поїзда:</label>
        <input type="number" name="train_id" required>
        <br>
        <label for="ticket_type">Тип квитка:</label>
        <input type="text" name="ticket_type" required>
        <br>
        <label for="cashier_id">ID Касира:</label>
        <input type="number" name="cashier_id" required>
        <br>
        <label for="price">Ціна:</label>
        <input type="number" name="price" required>
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
