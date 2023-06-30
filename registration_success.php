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
      redirectToPage('admin_dashboard.php');
      exit();
    case 'teacher':
      redirectToPage('teacher_dashboard.php');
      exit();
    case 'student':
      redirectToPage('student_dashboard.php');
      exit();
    default:
      // Если у пользователя нет определенной роли, перенаправляем на страницу выхода
      redirectToPage('logout.php');
      exit();
  }
}

// Получение кода подтверждения из GET-параметров
$confirmationCode = $_GET['confirmation_code'];

// Экранирование и очистка данных
$confirmationCode = escape($confirmationCode);

// Проверка кода подтверждения в базе данных
$query = "SELECT id FROM users WHERE confirmation_code = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('s', $confirmationCode);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

// Если код действителен, изменяем статус учетной записи на "Активен"
if ($userId) {
    $query = "UPDATE users SET status = 'active' WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['user_id'] = $userId; // Сохраняем идентификатор пользователя в сессии
    $_SESSION['role'] = 'student';

    // Перенаправляем на страницу с сообщением об успешном подтверждении регистрации
    redirectToPage('registration_confirmation_success.php');
    exit();
} else {
    echo 'Недействительный код подтверждения.';
}
?>


<!DOCTYPE html>
<html>
<head>
  <title><?php echo escapeHTML('Подтверждение регистрации'); ?></title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <div class="container">
    <h1>Подтверждение регистрации</h1>
    <p>Ваша регистрация успешно подтверждена.</p>
  </div>
</body>
</html>