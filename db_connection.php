<?php
// Параметры подключения к базе данных
$db_name = "test";
$db_user = "user";
$db_pass = "password";
$db_host = "localhost";

// Подключение к базе данных
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

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
?>
