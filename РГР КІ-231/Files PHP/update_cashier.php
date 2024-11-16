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

// Обробка редагування касира
if ($connectionSuccess && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cashier_id'], $_POST['name'], $_POST['year'], $_POST['month'], $_POST['day'], $_POST['phone_number'])) {
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
	
    // Оновлення даних касира
    if (isset($_POST['cashier_id'])) {
        $cashier_id = $_POST['cashier_id'];
        $stmt = $pdo->prepare("UPDATE cashier SET name = ?, date_of_birth = ?, phone_number = ? WHERE cashier_id = ?");
        $stmt->execute([$name, $date_of_birth, $phone_number, $cashier_id]);
        echo "Дані касира оновлено успішно!";
    }
}

// Отримання списку касирів для вибору
$query = "SELECT cashier_id, name FROM cashier";
$stmt = $pdo->prepare($query);
$stmt->execute();
$cashiers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оновити касира</title>
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
    <h1>Оновити касира</h1>
    
    <!-- Форма для вибору касира та редагування -->
    <form method="POST">
        <label for="cashier_id">Оберіть касира:</label>
        <select name="cashier_id" id="cashier_id" required>
            <?php foreach ($cashiers as $cashier): ?>
                <option value="<?php echo $cashier['cashier_id']; ?>"><?php echo htmlspecialchars($cashier['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Оновити">
    </form>

    <!-- Форма для редагування даних касира -->
    <h2>Дані касира</h2>
    <?php
    if (isset($_POST['cashier_id'])) {
        $cashier_id = $_POST['cashier_id'];
        $query = "SELECT * FROM cashier WHERE cashier_id = :cashier_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['cashier_id' => $cashier_id]);
        $cashier = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cashier):
    ?>
        <form method="POST" action="">
            <input type="hidden" name="cashier_id" value="<?php echo $cashier['cashier_id']; ?>">
            <label for="name">Ім'я:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($cashier['name']); ?>" required>
            <br>

            <!-- Поля для введення дати народження -->
            <label for="year">Рік народження:</label>
            <input type="text" name="year" value="<?php echo date('Y', strtotime($cashier['date_of_birth'])); ?>" placeholder="Рік (yyyy)" required>
            <br>
            <label for="month">Місяць:</label>
            <input type="text" name="month" value="<?php echo date('m', strtotime($cashier['date_of_birth'])); ?>" placeholder="Місяць (01-12)" required>
            <br>
            <label for="day">День:</label>
            <input type="text" name="day" value="<?php echo date('d', strtotime($cashier['date_of_birth'])); ?>" placeholder="День (01-31)" required>
            <br>

            <label for="phone_number">Номер телефону:</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($cashier['phone_number']); ?>" required>
            <br>
            <input type="submit" value="Зберегти зміни">
        </form>
    <?php
        else:
            echo "Касира не знайдено.";
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
