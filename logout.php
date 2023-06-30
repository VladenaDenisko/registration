<?php
session_start();
require_once 'db_connect.php';
require_once 'security.php';

// Разрушение текущей сессии пользователя
session_unset();
session_destroy();

// Перенаправление на главную страницу
redirectToLogin();
exit();
?>
