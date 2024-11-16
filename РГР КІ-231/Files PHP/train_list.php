<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання списку потягів
$query = "SELECT train_id, type FROM train";
$stmt = $pdo->prepare($query);
$stmt->execute();
$trains = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список потягів</title>
</head>
<body>
    <h1>Список наявних потягів</h1>
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
    <br>

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
