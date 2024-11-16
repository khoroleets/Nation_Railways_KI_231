<?php
include 'db_connection.php'; // Підключення до бази даних

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Отримуємо дані водіїв з фільтром
    $driver_query = "SELECT name, work_experience FROM driver";
    
    // Якщо форма з фільтром була надіслана
    if (isset($_POST['filter_drivers']) && !empty($_POST['driver_experience'])) {
        $driver_experience = $_POST['driver_experience']; // Отримуємо досвід роботи з поля вводу
        $driver_query .= " WHERE work_experience >= ?";
        $driver_stmt = $pdo->prepare($driver_query);
        $driver_stmt->execute([$driver_experience]);
    } else {
        $driver_stmt = $pdo->query($driver_query);
    }

    $drivers = $driver_stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список водіїв</title>
</head>
<body>
    <h1>Список водіїв</h1>
    <table border="1">
        <tr>
            <th>Ім'я</th>
            <th>Досвід роботи (роки)</th>
        </tr>
        <?php if (!empty($drivers)): ?>
            <?php foreach ($drivers as $driver): ?>
            <tr>
                <td><?php echo htmlspecialchars($driver['name']); ?></td>
                <td><?php echo htmlspecialchars($driver['work_experience']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2" style="text-align: center;">Водіїв не знайдено.</td>
            </tr>
        <?php endif; ?>
    </table>

    <!-- Форма для фільтрування водіїв за досвідом роботи -->
    <h2>Фільтрувати водіїв</h2>
    <form method="post">
        <input type="number" name="driver_experience" placeholder="Введіть мінімальний досвід роботи" required>
        <button type="submit" name="filter_drivers">Фільтрувати</button>
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
