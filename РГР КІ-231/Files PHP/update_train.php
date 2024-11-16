<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання списку потягів
$query = "SELECT train_id, type FROM train";
$stmt = $pdo->prepare($query);
$stmt->execute();
$trains = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['train_id'])) {
    $train_id = $_POST['train_id'];
    $type = $_POST['type'];

    // Оновлення даних потяга
    $query = "UPDATE train SET type = :type WHERE train_id = :train_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['type' => $type, 'train_id' => $train_id]);

    echo "Дані потяга оновлено успішно!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити потяг</title>
</head>
<body>
    <h1>Оновити потяг</h1>
    <form method="POST">
        <label for="train_id">Оберіть потяг за ID:</label>
        <select name="train_id" required>
            <?php foreach ($trains as $train): ?>
                <option value="<?php echo $train['train_id']; ?>"><?php echo htmlspecialchars($train['train_id'] . ' - ' . $train['type']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="type">Новий тип потяга:</label>
<input type="text" name="type" id="type" pattern="^[A-Za-zА-Яа-яЁёіІєЄїЇіґҐ]+$" title="Тип потяга має містити лише букви" required>
<br>
        <input type="submit" value="Оновити">
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
