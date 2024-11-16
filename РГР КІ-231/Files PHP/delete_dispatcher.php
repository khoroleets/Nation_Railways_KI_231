<?php
include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dispatcher_id'])) {
    $dispatcher_id = $_POST['dispatcher_id'];

    // Видалення диспетчера
    $query = "DELETE FROM dispatcher WHERE dispatcher_id = :dispatcher_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['dispatcher_id' => $dispatcher_id]);

    echo "Диспетчера видалено успішно!";
}

// Отримати список диспетчерів для вибору
$query = "SELECT dispatcher_id, name FROM dispatcher"; // Отримуємо ID та ім'я диспетчерів
$stmt = $pdo->prepare($query);
$stmt->execute();
$dispatchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити диспетчера</title>
</head>
<body>
    <h1>Видалити диспетчера</h1>
    <form method="POST">
        <label for="dispatcher_id">Оберіть диспетчера:</label>
        <select name="dispatcher_id" required>
            <?php foreach ($dispatchers as $dispatcher): ?>
                <option value="<?php echo $dispatcher['dispatcher_id']; ?>"><?php echo htmlspecialchars($dispatcher['name']); ?></option>
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
