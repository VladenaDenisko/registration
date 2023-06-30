<?php
<<<<<<< HEAD
    // Параметры подключения к базе данных
    $db_name = "db_name";
    $db_user = "db_user";
    $db_pass = "db_password";
    $db_host = "db";

    // Подключение к базе данных
    $mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Проверка соединения
    if ($mysqli->connect_errno) {
        die("Не удалось подключиться к базе данных: " . $mysqli->connect_error);
    }

    // Устанавливаем кодировку соединения
    $mysqli->set_charset("utf8mb4");

    // Экранирование специальных символов для безопасного использования в SQL-запросах
    function escape($string)
    {
        global $mysqli;
        return $mysqli->real_escape_string($string);
    }

    // Защита от XSS-атак
    function sanitize($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
=======
// Параметры подключения к базе данных
$db_name = "db_name";
$db_user = "db_user";
$db_pass = "db_password";
$db_host = "db";

// Подключение к базе данных
#$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
#$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Проверка соединения
if ($mysqli->connect_errno) {
    die("Не удалось подключиться к базе данных: " . $mysqli->connect_error);
}

// Устанавливаем кодировку соединения
$mysqli->set_charset("utf8mb4");

// Экранирование специальных символов для безопасного использования в SQL-запросах
function escape($string)
{
    global $mysqli;
    return $mysqli->real_escape_string($string);
}

// Защита от XSS-атак
function sanitize($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
>>>>>>> new_branch
?>
