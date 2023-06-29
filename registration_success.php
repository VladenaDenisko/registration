<?php
require_once 'db_connect.php';

session_start();

// Проверка, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
  // Если пользователь уже авторизован, перенаправляем на другую страницу в зависимости от его роли
  $role = $_SESSION['role'];
  switch ($role) {
    case 'admin':
      header('Location: admin_dashboard.php');
      break;
    case 'teacher':
      header('Location: teacher_dashboard.php');
      break;
    case 'student':
      header('Location: student_dashboard.php');
      break;
    default:
      // Если у пользователя нет определенной роли, перенаправляем на страницу выхода
      header('Location: logout.php');
      break;
  }
  exit();
}

// Получение кода подтверждения из GET-параметров
$confirmationCode = $_GET['code'];

// Проверка кода подтверждения в базе данных
// Если код действителен, изменяем статус учетной записи на "Активен"

// Перенаправление на страницу с сообщением об успешном подтверждении регистрации
header('Location: registration_confirmation_success.php');
exit();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Подтверждение регистрации</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Подключите ваш файл стилей (styles.css) -->
</head>
<body>
  <div class="container">
    <h1>Подтверждение регистрации</h1>
    <p>Ваша регистрация успешно подтверждена.</p>
  </div>
</body>
</html>
