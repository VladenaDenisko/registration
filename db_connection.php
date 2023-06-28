<?php
function getConnection()
{
    $dbHost = 'db';
    $dbName = 'db_name';
    $dbUser = 'db_user';
    $dbPass = 'db_password';

    // Устанавливаем соединение с базой данных
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

    if (!$conn) {
        die('Ошибка подключения к базе данных: ' . mysqli_connect_error());
    }

    // Устанавливаем кодировку соединения
    mysqli_set_charset($conn, 'utf8mb4');

    // Экранируем все данные перед выполнением запросов
    mysqli_real_escape_string($conn, $dbHost);
    mysqli_real_escape_string($conn, $dbName);
    mysqli_real_escape_string($conn, $dbUser);
    mysqli_real_escape_string($conn, $dbPass);

    // Используем подготовленные выражения для запросов
    mysqli_stmt_init($conn);

    // Отключаем автоматическое коммитирование транзакций
    mysqli_autocommit($conn, false);

    // Установка режима строгой проверки типов
    mysqli_options($conn, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);

    return $conn;
}
?>