<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід до системи</title>
</head>
<body>
    <h1>Увійдіть до системи</h1>

    <form method="POST" action="login_process.php">
        <label for="username">Логін:</label>
        <input type="text" name="username" required><br><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Увійти">
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
