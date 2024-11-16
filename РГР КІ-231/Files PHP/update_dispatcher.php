<?php
include 'db_connection.php'; // Підключення до бази даних

// Отримання даних диспетчера для редагування
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dispatcher_id'])) {
    $dispatcher_id = $_POST['dispatcher_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $salary = isset($_POST['salary']) ? $_POST['salary'] : '';

    // Оновлення даних диспетчера, якщо всі поля заповнені
    if ($name && $salary !== '') {
        // Перевірка на коректність імені (лише літери)
        if (!preg_match("/^[a-zA-Zа-яА-ЯіїєІЇЄ' ]+$/u", $name)) {
            echo "Помилка: Ім'я може містити тільки букви.";
            exit;
        }

        $query = "UPDATE dispatcher SET name = :name, salary = :salary WHERE dispatcher_id = :dispatcher_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $name,
            'salary' => $salary,
            'dispatcher_id' => $dispatcher_id
        ]);

        echo "Дані диспетчера оновлено успішно!";
    } else {
        echo "Будь ласка, заповніть усі поля.";
    }
}

// Отримати список диспетчерів для вибору
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
</head>
<body>
    <h1>Оновити диспетчера</h1>
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
            <input type="text" name="name" value="<?php echo htmlspecialchars($dispatcher['name']); ?>" required pattern="^[a-zA-Zа-яА-ЯіїєІЇЄ' ]+$" title="Ім'я повинно містити лише букви" />
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
