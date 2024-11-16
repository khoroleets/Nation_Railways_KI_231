<?php
include 'db_connection.php'; // Підключення до бази даних

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Отримуємо дані диспетчерів з фільтром
    $dispatcher_query = "SELECT name, salary FROM dispatcher";
    
    // Якщо форма з фільтром була надіслана
    if (isset($_POST['filter_dispatchers']) && !empty($_POST['dispatcher_salary'])) {
        $dispatcher_salary = $_POST['dispatcher_salary']; // Отримуємо зарплату з поля вводу
        $dispatcher_query .= " WHERE salary >= ?";
        $dispatcher_stmt = $pdo->prepare($dispatcher_query);
        $dispatcher_stmt->execute([$dispatcher_salary]);
    } else {
        $dispatcher_stmt = $pdo->query($dispatcher_query);
    }

    $dispatchers = $dispatcher_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список диспетчерів</title>
</head>
<body>
    <h1>Список диспетчерів</h1>
    <table border="1">
        <tr>
            <th>Ім'я</th>
            <th>Зарплата</th>
        </tr>
        <?php if (!empty($dispatchers)): ?>
            <?php foreach ($dispatchers as $dispatcher): ?>
            <tr>
                <td><?php echo htmlspecialchars($dispatcher['name']); ?></td>
                <td><?php echo htmlspecialchars($dispatcher['salary']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" style="text-align: center;">Диспетчерів не знайдено.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Форма для фільтрування диспетчерів за зарплатою -->
    <h2>Фільтрувати диспетчерів</h2>
    <form method="post">
        <input type="number" name="dispatcher_salary" placeholder="Введіть мінімальну зарплату" required>
        <button type="submit" name="filter_dispatchers">Фільтрувати</button>
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
