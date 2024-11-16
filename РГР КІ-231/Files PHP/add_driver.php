<?php
include 'db_connection.php'; // Підключення до бази даних

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $work_experience = $_POST['work_experience'];

    // Фільтрація імені (лише букви і пробіли)
    if (!preg_match("/^[a-zA-Zа-яА-ЯіІїЇєЄ' ]+$/u", $name)) {
        echo "Помилка: Некоректне ім'я. Ім'я повинно містити лише літери та пробіли.";
        exit;
    }

    // Фільтрація досвіду роботи (лише цілі числа більше або рівне нулю)
    if (!filter_var($work_experience, FILTER_VALIDATE_INT) || $work_experience < 1) {
        echo "Помилка: Некоректний досвід роботи. Введіть ціле число, яке не менше 1.";
        exit;
    }

    // Отримуємо найбільший driver_id
    $query = "SELECT MAX(driver_id) AS max_id FROM driver";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Визначаємо наступний driver_id
    $next_driver_id = ($result['max_id'] !== null) ? $result['max_id'] + 1 : 1;

    // Вставка нового водія з наступним driver_id
    $insert_query = "INSERT INTO driver (driver_id, name, work_experience) VALUES (:driver_id, :name, :work_experience)";
    $insert_stmt = $pdo->prepare($insert_query);
    $insert_stmt->execute([
        'driver_id' => $next_driver_id,
        'name' => $name,
        'work_experience' => $work_experience
    ]);

    echo "Водія додано успішно!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати водія</title>
</head>
<body>
    <h1>Додати нового водія</h1>
    <form method="POST">
        <label for="name">Ім'я:</label>
        <input type="text" name="name" placeholder="Ваше ім'я" required>
        <br>
        <label for="work_experience">Досвід роботи (роки):</label>
        <input type="number" name="work_experience" placeholder="Досвід роботи (з 1)" required>
        <br>
        <input type="submit" value="Додати">
    </form>

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
