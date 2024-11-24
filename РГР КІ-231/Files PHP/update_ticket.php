<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

// Отримання списку потягів
$query = "SELECT train_id, type FROM train";
$stmt = $pdo->prepare($query);
$stmt->execute();
$trains = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обробка оновлення потяга
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['train_id'], $_POST['type'])) {
    $train_id = $_POST['train_id'];
    $type = $_POST['type'];

    // Оновлення даних потяга
    $query = "UPDATE train SET type = :type WHERE train_id = :train_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['type' => $type, 'train_id' => $train_id]);

    $success_message = "Дані потяга з ID $train_id оновлено успішно!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити потяг</title>
    <style>
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .return-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #fff;
            color: #000;
            border: 2px solid #000;
            text-decoration: none;
            font-size: 1em;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
        }
        .return-button:hover {
            background-color: #f0f0f0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <h1>Оновити потяг</h1>

    <!-- Повідомлення про успіх -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>
	
	
    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-top: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>
	
    <!-- Форма для редагування потяга -->
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

    <div class="button-container">
        <a href="http://localhost/nation_railways/main.php" class="return-button">
            Повернутись до головної сторінки
        </a>
    </div>
</body>
</html>
