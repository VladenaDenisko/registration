<?php
require_once 'db_connect.php';

session_start();

// Проверка, авторизован ли пользователь
if (isset($_SESSION['user_id'])) {
  // Если пользователь авторизован, перенаправляем на другую страницу в зависимости от его роли
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

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Проверка введенных данных

  // Экранирование и очистка данных
  $email = escape($email);
  $password = escape($password);

  // Хеширование пароля
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Проверка соответствия данных в базе данных

  // Если данные верны, авторизуем пользователя и перенаправляем на соответствующую страницу
  $_SESSION['user_id'] = $userId; // Замените $userId на фактическое значение ID пользователя
  $_SESSION['role'] = $userRole; // Замените $userRole на фактическую роль пользователя

  // Перенаправление на соответствующую страницу
  switch ($userRole) {
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
?>

<!DOCTYPE html>
<html>
<head>
  <title>Главная страница</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Подключите ваш файл стилей (styles.css) -->
</head>
<body>
  <div class="container">
    <h1>Добро пожаловать на сайт!</h1>
    <p>Здесь можно ввести описание вашего сайта.</p>

    <div class="login-form">
      <h2>Войти</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="email" name="email" placeholder="Электронная почта" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
      </form>
      <p>Нет аккаунта? <a href="registration.php">Зарегистрироваться</a></p>
    </div>
  </div>
</body>
</html>
