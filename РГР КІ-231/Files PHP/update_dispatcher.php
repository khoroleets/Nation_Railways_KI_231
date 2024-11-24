<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include 'db_connection.php'; // Підключення до бази даних

$connectionSuccess = false; // Перемінна для відстеження статусу з'єднання
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connectionSuccess = true;
} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}

// Обробка редагування диспетчера
if ($connectionSuccess && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dispatcher_id'], $_POST['name'], $_POST['salary'])) {
    // Очищення та валідація даних
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $salary = trim($_POST['salary']);

    // Валідація імені
    if (!preg_match("/^[a-zA-Zа-яА-ЯіїєІЇЄ' ]{1,50}$/u", $name)) {
        echo "Помилка: Некоректне ім'я.";
        exit;
    }

    // Валідація зарплати
    if (!is_numeric($salary) || $salary < 0) {
        echo "Помилка: Некоректна зарплата.";
        exit;
    }

    // Оновлення даних диспетчера
    if (isset($_POST['dispatcher_id'])) {
        $dispatcher_id = $_POST['dispatcher_id'];
        $stmt = $pdo->prepare("UPDATE dispatcher SET name = ?, salary = ? WHERE dispatcher_id = ?");
        $stmt->execute([$name, $salary, $dispatcher_id]);
        $success_message = "Дані диспетчера оновлено успішно!";
    }
}

// Отримання списку диспетчерів для вибору
$query = "SELECT dispatcher_id, name FROM dispatcher";
$stmt = $pdo->prepare($query);
$stmt->execute();
$dispatchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити диспетчера</title>
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
    <h1>Оновити диспетчера</h1>

    <!-- Повідомлення про успіх або помилку -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
    <?php elseif (isset($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <!-- Форма для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <!-- Форма для вибору диспетчера та редагування -->
    <form method="POST">
        <label for="dispatcher_id">Оберіть диспетчера:</label>
        <select name="dispatcher_id" id="dispatcher_id" required>
            <?php foreach ($dispatchers as $dispatcher): ?>
                <option value="<?php echo $dispatcher['dispatcher_id']; ?>"><?php echo htmlspecialchars($dispatcher['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Оновити">
    </form>

    <!-- Форма для редагування даних диспетчера -->
    <h2>Дані диспетчера</h2>
    <?php
    if (isset($_POST['dispatcher_id'])) {
        $dispatcher_id = $_POST['dispatcher_id'];
        $query = "SELECT * FROM dispatcher WHERE dispatcher_id = :dispatcher_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['dispatcher_id' => $dispatcher_id]);
        $dispatcher = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dispatcher):
    ?>
        <form method="POST" action="">
            <input type="hidden" name="dispatcher_id" value="<?php echo $dispatcher['dispatcher_id']; ?>">
            <label for="name">Ім'я:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($dispatcher['name']); ?>" required>
            <br>

            <label for="salary">Зарплата:</label>
            <input type="number" step="0.01" name="salary" value="<?php echo htmlspecialchars($dispatcher['salary']); ?>" required>
            <br>
            <input type="submit" value="Зберегти зміни">
        </form>
    <?php
        else:
            echo "Диспетчера не знайдено.";
        endif;
    }
    ?>

    <div class="button-container">
        <a href="http://localhost/nation_railways/main.php" class="return-button">
            Повернутись до головної сторінки
        </a>
    </div>
</body>
</html>
