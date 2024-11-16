<?php
require 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Nation Railways</title>
    <style>
        /* Загальний стиль сторінки */
        body {
            font-family: Arial, sans-serif;
            color: #fff;
            background: url('railway_background.jpg') center/cover no-repeat fixed;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Забороняємо прокрутку */
        }

        /* Контейнер для рельс */
        .rails {
            position: absolute;
            bottom: -5px; /* Відстань віднизу до рельс */
            left: 0;
            width: 100%;
            height: 20px;
            display: flex; /* Використовуємо flex для розміщення рейок */
            justify-content: space-between; /* Вирівнюємо рейки по ширині */
        }

        .rail {
            width: 10%; /* Ширина кожної рейки */
            height: 10px; /* Висота рейки */
            background: #6c6c6c; /* Колір рейки */
            position: relative;
            border-radius: 2px; /* Краї рейки */
        }

        .rail:before,
        .rail:after {
            content: "";
            position: absolute;
            top: -5px; /* Висота над рейкою */
            left: 0;
            width: 100%;
            height: 5px; /* Висота для відображення підкладок */
            background: #4b4b4b; /* Колір підкладки */
            border-radius: 2px; /* Краї підкладки */
        }

        /* Анімація для поїзда */
        .train {
            position: absolute;
            bottom: -6px; /* Висота поїзда над рельсами */
            width: 230px; /* Збільшена ширина поїзда */
            height: 100px; /* Збільшена висота поїзда */
            background: url('train.png') no-repeat center center; /* Встановлюємо зображення */
            background-size: contain; /* Зображення адаптується під контейнер */
            animation: moveTrain 10s linear infinite; /* Анімація руху */
        }

        /* Анімація руху поїзда */
        @keyframes moveTrain {
            0% {
                left: -420px; /* Початкова позиція за межами лівої сторони */
            }
            100% {
                left: 100%; /* Кінцева позиція за межами правої сторони */
            }
        }

        /* Вітальний заголовок */
        h1 {
            font-size: 3em;
            text-align: center;
            color: #fdbb2d;
            margin-top: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            animation: fadeIn 3s ease-in-out;
        }

        /* Анімація для заголовка */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Основний контейнер */
        .container {
            max-width: 900px;
            width: 100%;
            padding: 20px;
            background-color: rgba(10, 10, 20, 0.85);
            border-radius: 12px;
            text-align: center;
            margin: 20px auto 0; /* Відступ зверху */
        }

        /* Стиль для повідомлення про з’єднання */
        .status-message {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #4CAF50;
            margin: 15px 0;
        }

        /* Іконка підтвердження з’єднання */
        .status-icon {
            font-size: 1.5em;
            color: #4CAF50;
            margin-left: 2px; /* Відступ між текстом і іконкою */
        }

        /* Іконка для помилки */
        .error-icon {
            font-size: 1.5em;
            color: #b21f1f;
            margin-right: 5px;
        }

        /* Кнопки */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }

        .button-row {
            margin-bottom: 20px; /* Відступ між рядками кнопок */
        }

        .button {
            padding: 15px 20px;
            background-color: #1a2a6c;
            color: #fdbb2d;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .button:hover {
            transform: scale(1.05);
            background-color: #fdbb2d;
            color: #1a2a6c;
        }

        /* Стиль для футера */
        footer {
            text-align: center;
            color: gray;
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="rails">
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
	<div class="rail"></div>
	<div class="rail"></div>
	<div class="rail"></div>
</div> <!-- Додаємо рельси -->

<div class="train"></div> <!-- Додаємо контур поїзда -->

<h1>Вас вітає "Nation Railways"</h1>

<div class="container">
    <?php if ($connectionSuccess): ?>
        <p>З’єднання з базою даних встановлено успішно
        <span class="status-icon">&#10004;</span> <!-- Галочка для успішного з'єднання -->
        <br>Розробники : Гаркуша, Маховик, Поздняк, Хоролець КІ-231</p>
    <?php else: ?>
        <p>Помилка підключення до бази даних
        <span class="error-icon">&#10060;</span>
        <br>Розробники: Гаркуша, Маховик, Поздняк, Хоролець КІ-231</p>
    <?php endif; ?>

    <div class="button-row">
        <div class="button-container">
            <a href="http://localhost/nation_railways/add_cashier.php" class="button">Додати касира</a>
            <a href="http://localhost/nation_railways/add_customer.php" class="button">Додати клієнта</a>
            <a href="http://localhost/nation_railways/add_dispatcher.php" class="button">Додати диспетчера</a>
            <a href="http://localhost/nation_railways/add_driver.php" class="button">Додати водія</a>
            <a href="http://localhost/nation_railways/add_ticket.php" class="button">Додати квиток</a>
            <a href="http://localhost/nation_railways/add_train.php" class="button">Додати потяг</a>
        </div>
    </div>

    <div class="button-row">
        <div class="button-container">
            <a href="http://localhost/nation_railways/cashier_list.php" class="button">Список касирів</a>
            <a href="http://localhost/nation_railways/customer_list.php" class="button">Список клієнтів</a>
            <a href="http://localhost/nation_railways/dispatcher_list.php" class="button">Список диспетчерів</a>
            <a href="http://localhost/nation_railways/driver_list.php" class="button">Список водіїв</a>
            <a href="http://localhost/nation_railways/ticket_list.php" class="button">Список квитків</a>
            <a href="http://localhost/nation_railways/train_list.php" class="button">Список потягів</a>
        </div>
    </div>

    <div class="button-row">
        <div class="button-container">
            <a href="http://localhost/nation_railways/delete_cashier.php" class="button">Видалити касира</a>
            <a href="http://localhost/nation_railways/delete_customer.php" class="button">Видалити клієнта</a>
            <a href="http://localhost/nation_railways/delete_dispatcher.php" class="button">Видалити диспетчера</a>
            <a href="http://localhost/nation_railways/delete_driver.php" class="button">Видалити водія</a>
            <a href="http://localhost/nation_railways/delete_ticket.php" class="button">Видалити квиток</a>
            <a href="http://localhost/nation_railways/delete_train.php" class="button">Видалити потяг</a>
        </div>
    </div>

    <div class="button-row">
        <div class="button-container">
            <a href="http://localhost/nation_railways/update_cashier.php" class="button">Оновити касира</a>
            <a href="http://localhost/nation_railways/update_customer.php" class="button">Оновити клієнта</a>
            <a href="http://localhost/nation_railways/update_dispatcher.php" class="button">Оновити диспетчера</a>
            <a href="http://localhost/nation_railways/update_driver.php" class="button">Оновити водія</a>
            <a href="http://localhost/nation_railways/update_ticket.php" class="button">Оновити квиток</a>
            <a href="http://localhost/nation_railways/update_train.php" class="button">Оновити потяг</a>
        </div>
    </div>
</div>

</body>
</html>
