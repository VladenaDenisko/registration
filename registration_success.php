<?php
require_once 'db_connect.php';
require_once 'security.php';

session_start();

// Проверка, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
  // Если пользователь уже авторизован, перенаправляем на другую страницу в зависимости от его роли
  $role = $_SESSION['role'];
  switch ($role) {
    case 'admin':
      header('Location: admin_dashboard.php');
      exit();
    case 'teacher':
      header('Location: teacher_dashboard.php');
      exit();
    case 'student':
      header('Location: student_dashboard.php');
      exit();
    default:
      // Если у пользователя нет определенной роли, перенаправляем на страницу выхода
      header('Location: logout.php');
      exit();
  }
}

// Получение кода подтверждения из GET-параметров
$confirmationCode = $_GET['confirmation_code'];

// Проверка кода подтверждения в базе данных
$query = "SELECT id FROM users WHERE confirmation_code = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $confirmationCode);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userId);
$stmt->fetch();

// Если код действителен, изменяем статус учетной записи на "Активен"
if ($userId) {
    $query = "UPDATE users SET status = 'active' WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    $_SESSION['user_id'] = $userId; // Сохраняем идентификатор пользователя в сессии
    $_SESSION['role'] = 'student';
    
    // Перенаправляем на страницу с сообщением об успешном подтверждении регистрации
    header('Location: registration_confirmation_success.php');
    exit();
} else {
    echo 'Недействительный код подтверждения.';
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Подтверждение регистрации</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Подтверждение регистрации</h1>
    <p>Ваша регистрация успешно подтверждена.</p>
  </div>
</body>
</html>