<?php
// Подключение файла db_connection.php
require_once 'db_connection.php';

// Функция для обработки регистрации пользователя
function register()
{
    // Получаем соединение с базой данных
    $conn = getConnection();

    // Получаем данные из формы
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Проверяем, существует ли пользователь с таким email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Пользователь с таким email уже существует
        echo "Пользователь с таким email уже существует!";
    } else {
        // Хешируем пароль
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $status = 1;

        // Вставляем нового пользователя в базу данных
        $sql = "INSERT INTO users (`email`, `password`, `role`, `status`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $email, $hashedPassword, $role, $status);
        $stmt->execute();
        if (empty($stmt->error)) {
            // Регистрация выполнена успешно
            echo "Регистрация выполнена успешно!";
            $conn->commit();
        } else {
            // Ошибка при регистрации
            echo "Ошибка при регистрации: " . mysqli_stmt_error($stmt);
        }
    }

    // Закрываем подготовленное выражение
    mysqli_stmt_close($stmt);

    // Закрываем соединение с базой данных
    mysqli_close($conn);
}


// Проверяем, был ли отправлен запрос на регистрацию
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    register();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
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
    </style>
</head>
<body>
<h1>Регистрация</h1>
<p>Заполните форму ниже, чтобы зарегистрироваться:</p>

<form action="register.php" method="POST">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required>

    <label for="role">Роль:</label>
    <select id="role" name="role">
        <option value="admin">Администратор</option>
        <option value="teacher">Преподаватель</option>
        <option value="user">Пользователь</option>
    </select>

</form>
</body>
</html>
