<?php
include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];

    // Видалення водія
    $query = "DELETE FROM driver WHERE driver_id = :driver_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['driver_id' => $driver_id]);

    echo "Водія видалено успішно!";
}

// Отримати список водіїв для вибору
$query = "SELECT driver_id, name FROM driver"; // Отримуємо ID та ім'я водіїв
$stmt = $pdo->prepare($query);
$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити водія</title>
</head>
<body>
    <h1>Видалити водія</h1>
    <form method="POST">
        <label for="driver_id">Оберіть водія:</label>
        <select name="driver_id" required>
            <?php foreach ($drivers as $driver): ?>
                <option value="<?php echo $driver['driver_id']; ?>"><?php echo htmlspecialchars($driver['name']); ?></option>
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
