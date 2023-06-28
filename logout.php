<?php
session_start();

unset($_SESSION['user_id']);
unset($_SESSION['user_email']);
// Разрушаем текущую сессию
session_destroy();

// Перенаправляем на главную страницу
header('Location: index.php');
exit();
?>
