<?php 
session_start();
include 'db_connection.php'; // Підключення до бази даних

$connectionSuccess = false; // Перемінна для відстеження статусу з'єднання
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connectionSuccess = true;
} catch (PDOException $e) {
    echo "Помилка підключення: " . $e->getMessage();
}

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
		
	if ($year < 1900 || $year > 2024) 
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
	
    // Отримання найбільшого customer_id
    $stmt = $pdo->query("SELECT MAX(customer_id) as max_id FROM customer");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $next_customer_id = isset($row['max_id']) ? $row['max_id'] + 1 : 11;

    // Додавання нового клієнта з отриманим customer_id
    $stmt = $pdo->prepare("INSERT INTO customer (customer_id, name, date_of_birth, phone_number) VALUES (?, ?, ?, ?)");
    $stmt->execute([$next_customer_id, $name, $date_of_birth, $phone_number]);
    echo "Клієнта додано успішно!";
} elseif ($connectionSuccess) {
    echo "";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додавання клієнта</title>
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
    <h1>Зареєструвати нового клієнта</h1>
    <form method="POST">
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
        <input type="submit" value="Зареєструвати">
    </form>

    <div class="button-container">
        <a href="http://localhost/nation_railways/main.php" class="return-button">
            Повернутись до головної сторінки
        </a>
    </div>
</body>
</html>
