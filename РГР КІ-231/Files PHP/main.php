<?php
require 'db_connection.php';
session_start(); // Початок сесії
$isLoggedIn = isset($_SESSION['user_id']); // Якщо є user_id в сесії, то користувач авторизований
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
/* Стиль для погоди */
.weather-scroll {
    position: fixed;  /* Фіксоване позиціонування */
    top: 10px;           /* Встановлюємо на самий верх */
    left: 0;          /* Збоку до самого лівого краю */
    width: 100vw;     /* Ширина на весь екран */
    background-color: rgba(0, 0, 0, 0.5);  /* Темний фон з прозорістю */
    color: #fff;      /* Білий текст */
    padding: 5px 0;   /* Відступи зверху та знизу */
    font-size: 0.8em;  /* Зменшити розмір тексту */
    white-space: nowrap;
    overflow: hidden;
    z-index: 100;     /* Забезпечує, що погода буде на передньому плані */
    text-align: center;  /* Текст вирівнюємо ліворуч */
}

        /* Основний контейнер */
        .container {
            position: relative;
            top: -20px;
            max-width: 900px;
            width: 100%;
            padding: 20px;
            background-color: rgba(10, 10, 20, 0.85);
            border-radius: 12px;
            text-align: center;
            margin: 10px auto 0;
            padding-bottom: 20px;
        }


        .status-message {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
            color: #4CAF50;
            margin: 15px 0;
        }

        .status-icon {
            font-size: 1.5em;
            color: #4CAF50;
            margin-left: 2px;
        }

        .error-icon {
            font-size: 1.5em;
            color: #b21f1f;
            margin-right: 5px;
        }
		
.buttons-container {
    display: grid; /* Використання grid для точного розташування */
    grid-template-columns: repeat(5, auto); /* 4 кнопки в ряд */
    gap: 15px; /* Відстань між кнопками */
    justify-content: center; /* Центрування кнопок по горизонталі */
    align-items: center; /* Центрування кнопок по вертикалі */
    margin: 20px; /* Відступ від країв контейнера */
}

        .button-row {
            margin-bottom: 20px;
        }
		
/* Текст для анімації */
.weather-text {
    display: inline-block;
    animation: moveText 18s linear infinite;
    font-size: 0.9em;  /* Зменшити розмір шрифта тексту */
}

/* Анімація руху тексту */
@keyframes moveText {
    0% {
        transform: translateX(300%); /* Починається справа */
    }
    100% {
        transform: translateX(-300%); /* Переміщається вліво */
    }
    1000% {
        transform: translateX(300%); /* Повертається праворуч */
    }
}

        /* Контейнер для рельс */
        .rails {
            position: absolute;
            bottom: -5px; /* Відстань віднизу до рельс */
            left: 0;
            width: 100%;
            height: 20px;
            display: flex;
            justify-content: space-between;
        }

        .rail {
            width: 10%;
            height: 10px;
            background: #6c6c6c;
            position: relative;
            border-radius: 2px;
        }

        .rail:before,
        .rail:after {
            content: "";
            position: absolute;
            top: -5px;
            left: 0;
            width: 100%;
            height: 5px;
            background: #4b4b4b;
            border-radius: 2px;
        }

        /* Анімація для поїзда */
        .train {
            position: absolute;
            bottom: -6px;
            width: 230px;
            height: 100px;
            background: url('train.png') no-repeat center center;
            background-size: contain;
            animation: moveTrain 10s linear infinite;
        }

        @keyframes moveTrain {
            0% {
                left: -420px;
            }
            100% {
                left: 100%;
            }
        }
		
        /* Контейнер для користувача */
        .container.user {
			position: relative;
            top: -20px;
            max-width: 400px;
			width: 100%;
            padding: 20px;
            background-color: rgba(10, 10, 20, 0.85);
            border-radius: 12px;
            text-align: center;
            margin: 20px auto 0;
            padding-bottom: 40px;
			
        }
		
        /* Кнопки */
.button {
    padding: 10px 15px;
    background-color: #1a2a6c;
    color: #fdbb2d;
    text-decoration: none;
    font-size: 1em;
    border-radius: 8px;
    display: inline-block;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

        /* Підсвічування кнопок */
        .button:hover {
    background-color: #fdbb2d;
    color: #1a2a6c;
    box-shadow: 0 4px 15px rgba(253, 187, 45, 0.75); /* Додаємо тінь */
    transform: scale(1.05); /* Трохи збільшуємо кнопку */
}

        /* Кнопка "Вийти" */
.logout-button {
    padding: 5px 10px;
    background-color: #1a2a6c;
    color: white;
    font-size: 0.8em; /* Зменшений розмір шрифту */
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    display: block;
    width: 150px; /* Ширина кнопки */
    text-align: center;
    position: absolute;
    top: 10px; /* Відстань від верхнього краю */
    right: 10px; /* Відстань від правого краю */
    z-index: 200; /* Забезпечує, що кнопка буде поверх інших елементів */
    transition: background-color 0.3s;
}

        .logout-button:hover {
            background: #f55;
            color: #fff;
        }
		
		        footer {
            text-align: center;
            color: gray;
            margin-top: 20px;
            font-size: 0.9em;
        }
		
/* Вітальний заголовок для адміністратора */
h1.admin {
    font-size: 3em;
    text-align: center;
    color: #fdbb2d;
    margin-top: 70px; /* Менший відступ зверху */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
    animation: fadeIn 3s ease-in-out;
	padding-bottom: 10px;
}

/* Вітальний заголовок для користувача */
h1.user {
    font-size: 3em;
    text-align: center;
    color: #fdbb2d;
    margin-top: 150px; /* Більший відступ зверху */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
    animation: fadeIn 3s ease-in-out;
	padding-bottom: 10px;
	
}

/* Анімація для заголовків */
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Кнопка "Увійти" */
.login-button {
    padding: 5px 10px;
    background-color: #1a2a6c;
    color: white;
    font-size: 0.8em; /* Зменшений розмір шрифту */
    font-weight: bold;
    border-radius: 8px;
    text-decoration: none;
    display: block;
    width: 150px; /* Ширина кнопки */
    text-align: center;
    position: absolute;
    top: 10px; /* Відстань від верхнього краю */
    right: 10px; /* Відстань від правого краю */
    z-index: 200; /* Забезпечує, що кнопка буде поверх інших елементів */
    transition: background-color 0.3s;
}

.login-button:hover {
    background-color: #fdbb2d;
    color: #1a2a6c;
}


		
    </style>
</head>
<body>

<!-- Погода стрічка -->
<div class="weather-scroll">
    <div id="weather-text" class="weather-text"></div>
</div>

<div class="rails">
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
    <div class="rail"></div>
</div>

<div class="train"></div>

<?php if ($isLoggedIn): ?>
    <h1 class="admin">Вас вітає "Nation Railways"</h1>
	<a href="logout.php" class=""></a>
<?php else: ?>
    <h1 class="user">Вас вітає "Nation Railways"</h1>
	<!-- Кнопка "Увійти" -->
    <a href="login.php" class=""></a>
<?php endif; ?>


<div class="container">
    <?php if ($connectionSuccess): ?>
        <p>З’єднання з базою даних встановлено успішно
        <span class="status-icon">&#10004;</span>
        <br>Розробники : Гаркуша, Маховик, Поздняк, Хоролець КІ-231</p>
    <?php else: ?>
        <p>Помилка підключення до бази даних
        <span class="error-icon">&#10060;</span>
        <br>Розробники: Гаркуша, Маховик, Поздняк, Хоролець КІ-231</p>
    <?php endif; ?>
	
    <?php if ($isLoggedIn): ?>
        <h2>Адміністратор</h2>
        <div class="buttons-container">
    <a href="add_cashier.php" class="button">Додати касира</a>
    <a href="add_customer.php" class="button">Додати клієнта</a>
    <a href="add_driver.php" class="button">Додати водія</a>
    <a href="add_ticket.php" class="button">Додати квиток</a>
    <a href="add_train.php" class="button">Додати потяг</a>
    <a href="list_cashiers.php" class="button">Список касирів</a>
    <a href="list_customers.php" class="button">Список клієнтів</a>
    <a href="list_drivers.php" class="button">Список водіїв</a>
    <a href="list_tickets.php" class="button">Список квитків</a>
    <a href="list_trains.php" class="button">Список потягів</a>
    <a href="delete_cashier.php" class="button">Видалити касира</a>
    <a href="delete_customer.php" class="button">Видалити клієнта</a>
    <a href="delete_dispatcher.php" class="button">Видалити диспетчера</a>
    <a href="delete_driver.php" class="button">Видалити водія</a>
    <a href="delete_train.php" class="button">Видалити потяг</a>
    <a href="update_cashier.php" class="button">Оновити касира</a>
    <a href="update_customer.php" class="button">Оновити клієнта</a>
    <a href="update_dispatcher.php" class="button">Оновити диспетчера</a>
    <a href="update_driver.php" class="button">Оновити водія</a>
    <a href="update_train.php" class="button">Оновити потяг</a>
</div>

		<div class="logout-container">
        <a href="logout.php" class="logout-button">Вийти</a>
    </div>
	
        <a href="logout.php" class="logout-button">Вийти</a>
    <?php else: ?>
        <h2>Гість</h2>
        <div class="buttons-container">
            <a href="add_customer.php" class="button">Додати клієнта</a>
            <a href="driver_list.php" class="button">Список водіїв</a>
            <a href="ticket_list.php" class="button">Список квитків</a>
            <a href="train_list.php" class="button">Список потягів</a>
        </div>
        <div class="login-container">
        <a href="login.php" class="login-button">Увійти</a>
    </div>
    <?php endif; ?>
</div>

<script>
    // JavaScript для отримання та відображення погоди
    const weatherText = document.getElementById('weather-text');

    async function fetchWeather() {
        const apiKey = 'bfa7ab7b9296e9fb95e9ae34d0228731';
        const latitude = 51.50513190260735; // Широта
        const longitude = 31.335461701814086; // Довгота
        const url = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric&lang=uk`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data.main) {
                weatherText.textContent = `Погода в Чернігові: ${data.weather[0].description}, Температура: ${data.main.temp}°C`;
            }
        } catch (error) {
            weatherText.textContent = "Не вдалося отримати дані про погоду.";
        }
    }

    fetchWeather();
</script>

</body>
</html>
