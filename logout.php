<?php
session_start();
require_once 'db_connect.php';
<<<<<<< HEAD
require_once 'security.php';
=======
>>>>>>> new_branch

// Разрушение текущей сессии пользователя
session_unset();
session_destroy();

// Перенаправление на главную страницу
<<<<<<< HEAD
redirectToLogin();
=======
header("Location: index.php");
>>>>>>> new_branch
exit();
?>
