<?php
// Подключение файла db_connection.php
require_once 'db_connection.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Перенаправление на страницу входа, если пользователь не является преподавателем
    exit();
}
// Функция для добавления записи в таблицу предметов
function addSubject($name, $description)
{
    // Получение соединения с базой данных
    $conn = getConnection();

    // Начало транзакции
    mysqli_autocommit($conn, false);

    // Подготовка SQL-запроса
    $sql = "INSERT INTO subjects (name, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Привязка параметров
    mysqli_stmt_bind_param($stmt, "ss", $name, $description);

    // Выполнение SQL-запроса
    if (mysqli_stmt_execute($stmt)) {
        echo "Запись успешно добавлена!";
        // Завершение транзакции и сохранение изменений
        mysqli_commit($conn);
    } else {
        echo "Ошибка при добавлении записи: " . mysqli_stmt_error($stmt);
        // Откат транзакции в случае ошибки
        mysqli_rollback($conn);
    }

    // Закрытие подготовленного выражения
    mysqli_stmt_close($stmt);

    // Закрытие соединения с базой данных
    mysqli_close($conn);
}

// Функция для удаления записи из таблицы предметов
function deleteSubject($id)
{
    // Получение соединения с базой данных
    $conn = getConnection();

    // Начало транзакции
    mysqli_autocommit($conn, false);

    // Подготовка SQL-запроса
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Привязка параметров
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Выполнение SQL-запроса
    if (mysqli_stmt_execute($stmt)) {
        echo "Запись успешно удалена!";
        // Завершение транзакции и сохранение изменений
        mysqli_commit($conn);
    } else {
        echo "Ошибка при удалении записи: " . mysqli_stmt_error($stmt);
        // Откат транзакции в случае ошибки
        mysqli_rollback($conn);
    }

    // Закрытие подготовленного выражения
    mysqli_stmt_close($stmt);

    // Закрытие соединения с базой данных
    mysqli_close($conn);
}

// Проверяем, была ли отправлена форма добавления предмета
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subject'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Вызов функции для добавления предмета
    addSubject($name, $description);
}

// Проверяем, была ли отправлена форма удаления предмета
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_subject'])) {
    $id = $_POST['id'];

    // Вызов функции для удаления предмета
    deleteSubject($id);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Страница администратора</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
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

        .delete-button {
            padding: 8px 16px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Страница администратора</h1>

    <h2>Таблица предметов</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Действия</th>
        </tr>
        <?php
        // Запрос на получение данных из таблицы предметов
        $conn = getConnection();
        $sql = "SELECT * FROM subjects ORDER BY name ASC";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "<td>";
            echo "<form method='POST' action=''>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' name='delete_subject' class='delete-button'>Удалить</button>
                      </form>";
            echo "</td>";
            echo "</tr>";
        }

        // Закрытие соединения с базой данных
        mysqli_close($conn);
        ?>
    </table>

    <h2>Добавить предмет</h2>
    <form method="POST" action="">
        <label for="name">Название:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="description">Описание:</label>
        <textarea name="description" id="description" required></textarea><br>
        <button type="submit" name="add_subject">Добавить</button>
    </form>
</div>
</body>
</html>
