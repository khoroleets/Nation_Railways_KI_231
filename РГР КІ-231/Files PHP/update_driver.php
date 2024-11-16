<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання даних водія для редагування
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['driver_id'])) {
    $driver_id = $_POST['driver_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $work_experience = isset($_POST['work_experience']) ? $_POST['work_experience'] : '';

    // Оновлення даних водія, якщо всі поля заповнені
    if ($name && $work_experience !== '') {
        // Перевірка на коректність імені (лише літери)
        if (!preg_match("/^[a-zA-Zа-яА-ЯіїєІЇЄ' ]+$/u", $name)) {
            echo "Помилка: Ім'я може містити тільки букви.";
            exit;
        }

        // Перевірка на числове значення досвіду роботи
        if (!is_numeric($work_experience) || $work_experience < 0) {
            echo "Помилка: Досвід роботи має бути числом більше або рівним нулю.";
            exit;
        }

        $query = "UPDATE driver SET name = :name, work_experience = :work_experience WHERE driver_id = :driver_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $name,
            'work_experience' => $work_experience,
            'driver_id' => $driver_id
        ]);

        echo "Дані водія оновлено успішно!";
    } else {
        echo "Будь ласка, заповніть усі поля.";
    }
}

// Отримати список водіїв для вибору
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
</head>
<body>
    <h1>Оновити водія</h1>
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
