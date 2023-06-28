<?php
// Подключение файла db_connection.php
require_once 'db_connection.php';

// Запуск сессии
session_start();

// Функция для обработки входа пользователя
function login()
{
    // Получаем соединение с базой данных
    $conn = getConnection();

    // Получаем данные из формы
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подготовленный SQL-запрос для выборки пользователя по email
    $sql = "SELECT * FROM users WHERE email = ?";

    // Создаем подготовленное выражение
    $stmt = mysqli_prepare($conn, $sql);

    // Привязка значения параметра
    mysqli_stmt_bind_param($stmt, "s", $email);

    // Выполняем запрос
    mysqli_stmt_execute($stmt);

    // Получаем результаты
    $result = mysqli_stmt_get_result($stmt);

    // Проверяем наличие пользователя
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Проверяем пароль
        if (password_verify($password, $row['password'])) {
            // Вход выполнен успешно
            echo "Успешный вход!";

            // Сохраняем информацию о вошедшем пользователе в сессии
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Дополнительные действия после успешного входа
            // Например, перенаправление на другую страницу
            header("Location: welcome.php");
            exit();
        } else {
            // Неправильный пароль
            echo "Неправильный пароль!";
        }
    } else {
        // Пользователь не найден
        echo "Пользователь не найден!";
    }

    // Закрываем подготовленное выражение
    mysqli_stmt_close($stmt);

    // Закрываем соединение с базой данных
    mysqli_close($conn);
}

// Проверяем, был ли отправлен запрос на вход
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    login();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"] {
            width: 300px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<h1>Добро пожаловать на наш сайт!</h1>
<p>Здесь вы можете найти краткое описание сайта и предложение для входа или регистрации.</p>

<h2>Вход</h2>
<form action="login.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Войти</button>
</form>

<p>Еще нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</body>
</html>