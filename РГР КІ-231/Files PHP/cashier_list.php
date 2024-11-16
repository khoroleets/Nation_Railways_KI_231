<?php
include 'db_connection.php'; // Підключення до бази даних

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Отримуємо дані касирів
    $cashier_query = "SELECT name, phone_number FROM cashier";
    if (isset($_POST['filter_cashiers']) && !empty($_POST['cashier_phone_number'])) {
        $cashier_phone_number = $_POST['cashier_phone_number'];
        $cashier_query .= " WHERE phone_number LIKE ?";
        $cashier_stmt = $pdo->prepare($cashier_query);
        $cashier_stmt->execute(["%$cashier_phone_number%"]);
    } else {
        $cashier_stmt = $pdo->query($cashier_query);
    }

    $cashiers = $cashier_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Перевірка наявності касирів
    if (isset($_POST['filter_cashiers']) && !empty($_POST['cashier_phone_number']) && empty($cashiers)) {
        $no_cashiers_found = true; // Показуємо повідомлення про відсутність касирів
    }

} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список касирів</title>
</head>
<body>
    <h1>Список касирів</h1>
    <table border="1">
        <tr>
            <th>Ім'я</th>
            <th>Номер телефону</th>
        </tr>
        <?php if (!empty($cashiers)): ?>
            <?php foreach ($cashiers as $cashier): ?>
            <tr>
                <td><?php echo htmlspecialchars($cashier['name']); ?></td>
                <td><?php echo htmlspecialchars($cashier['phone_number']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <?php if (isset($no_cashiers_found)): ?>
                <tr>
                    <td colspan="2" style="text-align: center;">Касирів з таким номером телефону не знайдено.</td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>
    </table>

    <!-- Форма фільтрування касирів за номером телефону -->
    <h2>Фільтрувати касирів</h2>
    <form method="post">
        <input type="text" name="cashier_phone_number" placeholder="Фільтр за номером телефону">
        <button type="submit" name="filter_cashiers">Фільтрувати</button>
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
