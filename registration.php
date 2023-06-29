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

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = $_POST['full_name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Проверка введенных данных

  // Экранирование и очистка данных
  $fullName = escape($fullName);
  $email = escape($email);
  $password = escape($password);

  // Хеширование пароля
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Сохранение данных пользователя в базе данных

  // Генерация кода для подтверждения регистрации
  $confirmationCode = generateConfirmationCode();

  // Сохранение данных пользователя и кода подтверждения в базе данных

  // Отправка письма с кодом для подтверждения регистрации

  // Перенаправление на страницу с сообщением об успешной регистрации
  header('Location: registration_success.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Страница регистрации</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Подключите ваш файл стилей (styles.css) -->
</head>
<body>
  <div class="container">
    <h1>Регистрация</h1>
    <p>Заполните форму ниже для регистрации на сайте.</p>

    <div class="registration-form">
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="text" name="full_name" placeholder="Полное имя" required>
        <input type="email" name="email" placeholder="Электронная почта" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Зарегистрироваться</button>
      </form>
    </div>
  </div>
</body>
</html>
