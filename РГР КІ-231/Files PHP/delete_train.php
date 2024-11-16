<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання списку потягів
$query = "SELECT train_id, type FROM train";
$stmt = $pdo->prepare($query);
$stmt->execute();
$trains = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['train_id'])) {
    $train_id = $_POST['train_id'];

    // Видалення потяга
    $query = "DELETE FROM train WHERE train_id = :train_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['train_id' => $train_id]);

    echo "Потяг видалено успішно!";
}

// Отримання списку потягів для відображення
$display_trains = false;
if (isset($_GET['show_trains'])) {
    $display_trains = true;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Видалити потяг</title>
</head>
<body>
    <h1>Видалити потяг</h1>
    <form method="POST">
        <label for="train_id">Оберіть потяг за його ID:</label>
        <select name="train_id" required>
            <?php foreach ($trains as $train): ?>
                <option value="<?php echo $train['train_id']; ?>"><?php echo htmlspecialchars($train['train_id']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Видалити">
    </form>
    <br>
    
    <form method="GET">
        <input type="hidden" name="show_trains" value="1">
        <input type="submit" value="Показати наявні потяги">
    </form>
    
    <?php if ($display_trains): ?>
        <h2>Список наявних потягів</h2>
        <table border="1">
            <tr>
                <th>ID потяга</th>
                <th>Тип потяга</th>
            </tr>
            <?php foreach ($trains as $train): ?>
                <tr>
                    <td><?php echo htmlspecialchars($train['train_id']); ?></td>
                    <td><?php echo htmlspecialchars($train['type']); ?></td>
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
