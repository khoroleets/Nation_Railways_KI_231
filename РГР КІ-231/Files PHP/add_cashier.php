<?php
session_start(); // Початок сесії

// Якщо користувач не авторизований, перенаправляємо на сторінку входу
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI']; // Зберігаємо поточну сторінку
    header("Location: login.php"); // Перенаправляємо на сторінку входу
    exit;
}

include('auth.php'); // Включаємо перевірку авторизації

include 'db_connection.php'; // Підключення до бази даних

$connectionSuccess = false; // Перемінна для відстеження статусу з'єднання
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connectionSuccess = true;
} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
    exit;
}

// Якщо запит POST, виконуємо логіку додавання касира
if ($connectionSuccess && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'], $_POST['year'], $_POST['month'], $_POST['day'], $_POST['phone_number'])) {
    // Очищення та валідація даних
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $year = trim($_POST['year']);
    $month = trim($_POST['month']);
    $day = trim($_POST['day']);
    $phone_number = trim($_POST['phone_number']);

    // Валідація імені
    if (!preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄ' ]{1,50}$/u", $name)) {
        echo "Помилка: Некоректне ім'я.";
        exit;
    }

    // Валідація року, місяця та дня
    if (!preg_match("/^\d{4}$/", $year) || !preg_match("/^(0[1-9]|1[0-2])$/", $month) || !preg_match("/^(0[1-9]|[12][0-9]|3[01])$/", $day)) {
        echo "Помилка: Некоректна дата. Введіть дату у форматі рік-місяць-день.";
        exit;
    }

    if ($year < 1900 || $year > 2024) {
        echo "Помилка: Рік повинен бути в межах з 1900 по 2024.";
        exit;
    }

    // Перевірка на коректність дати
    $date_of_birth = $year . '-' . $month . '-' . $day;
    if (!checkdate($month, $day, $year)) {
        echo "Помилка: Некоректна дата народження.";
        exit;
    }

    // Валідація номера телефону
    if (!preg_match("/^\+?[0-9]{10,15}$/", $phone_number)) {
        echo "Помилка: Некоректний номер телефону.";
        exit;
    }

    // Фільтрація номера через FILTER_SANITIZE_NUMBER_INT
    $phone_number = filter_var($phone_number, FILTER_SANITIZE_NUMBER_INT);

    // Отримання найбільшого cashier_id
    $stmt = $pdo->query("SELECT MAX(cashier_id) as max_id FROM cashier");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_cashier_id = isset($row['max_id']) ? $row['max_id'] + 1 : 11;

    // Додавання нового касира
    $stmt = $pdo->prepare("INSERT INTO cashier (cashier_id, name, date_of_birth, phone_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$next_cashier_id, $name, $date_of_birth, $phone_number]);
    echo "Касира додано успішно!";
} elseif ($connectionSuccess) {
    // Відображення форми, якщо запит не POST
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати касира</title>
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
    <h1>Додати нового касира</h1>

    <!-- Кнопка для виходу з системи -->
    <form method="POST" action="logout.php" style="margin-bottom: 20px;">
        <input type="submit" value="Вийти з системи">
    </form>

    <!-- Форма для додавання касира -->
    <form method="POST" action="add_cashier.php">
        <label for="name">Ім'я:</label>
        <input type="text" name="name" placeholder="Ваше ім'я" required>
        <br>
        <label for="year">Рік народження:</label>
        <input type="text" name="year" placeholder="Рік (yyyy)" required>
        <br>
        <label for="month">Місяць:</label>
        <input type="text" name="month" placeholder="Місяць (01-12)" required>
        <br>
        <label for="day">День:</label>
        <input type="text" name="day" placeholder="День (01-31)" required>
        <br>
        <label for="phone_number">Номер телефону:</label>
        <input type="text" name="phone_number" placeholder="Ваш номер телефону (з 0)" required>
        <br>
        <input type="submit" value="Додати касира">
    </form>

    <!-- Кнопка для повернення на головну сторінку -->
    <div class="button-container">
        <a href="main.php" class="return-button">
            Повернутись до головної сторінки
        </a>
    </div>
</body>
</html>
<?php
}
?>
