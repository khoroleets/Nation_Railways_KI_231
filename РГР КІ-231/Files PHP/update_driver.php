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

// Обробка редагування водія
if ($connectionSuccess && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['driver_id'], $_POST['name'], $_POST['work_experience'])) {
    // Очищення та валідація даних
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $work_experience = trim($_POST['work_experience']);

    // Валідація імені
    if (!preg_match("/^[a-zA-Zа-яА-ЯіїєІЇЄ' ]{1,50}$/u", $name)) {
        echo "Помилка: Некоректне ім'я.";
        exit;
    }

    // Валідація досвіду роботи
    if (!is_numeric($work_experience) || $work_experience < 0) {
        echo "Помилка: Досвід роботи має бути числом більше або рівним нулю.";
        exit;
    }

    // Оновлення даних водія
    if (isset($_POST['driver_id'])) {
        $driver_id = $_POST['driver_id'];
        $stmt = $pdo->prepare("UPDATE driver SET name = ?, work_experience = ? WHERE driver_id = ?");
        $stmt->execute([$name, $work_experience, $driver_id]);
        $success_message = "Дані водія оновлено успішно!";
    }
}

// Отримання списку водіїв для вибору
$query = "SELECT driver_id, name FROM driver";
$stmt = $pdo->prepare($query);
$stmt->execute();
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити водія</title>
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
    <h1>Оновити водія</h1>

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

    <!-- Форма для вибору водія та редагування -->
    <form method="POST">
        <label for="driver_id">Оберіть водія:</label>
        <select name="driver_id" id="driver_id" required>
            <?php foreach ($drivers as $driver): ?>
                <option value="<?php echo $driver['driver_id']; ?>"><?php echo htmlspecialchars($driver['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Оновити">
    </form>

    <!-- Форма для редагування даних водія -->
    <h2>Дані водія</h2>
    <?php
    if (isset($_POST['driver_id'])) {
        $driver_id = $_POST['driver_id'];
        $query = "SELECT * FROM driver WHERE driver_id = :driver_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['driver_id' => $driver_id]);
        $driver = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($driver):
    ?>
        <form method="POST" action="">
            <input type="hidden" name="driver_id" value="<?php echo $driver['driver_id']; ?>">
            <label for="name">Ім'я:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($driver['name']); ?>" required pattern="^[a-zA-Zа-яА-ЯіїєІЇЄ' ]+$" title="Ім'я повинно містити лише букви" />
            <br>
            <label for="work_experience">Досвід роботи (роки):</label>
            <input type="number" name="work_experience" value="<?php echo htmlspecialchars($driver['work_experience']); ?>" required min="0" title="Досвід роботи має бути числом більше або рівним нулю" />
            <br>
            <input type="submit" value="Зберегти зміни">
        </form>
    <?php
        else:
            echo "Водія не знайдено.";
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
