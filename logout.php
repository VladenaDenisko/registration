<?php
session_start();
require_once 'db_connect.php';

// Разрушение текущей сессии пользователя
session_unset();
session_destroy();

// Перенаправление на главную страницу
header("Location: index.php");
exit();
?>
