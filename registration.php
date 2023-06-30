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
      redirectToPage('Location: admin_dashboard.php');
      break;
    case 'teacher':
      redirectToPage('Location: teacher_dashboard.php');
      break;
    case 'student':
      redirectToPage('Location: student_dashboard.php');
      break;
    default:
      // Если у пользователя нет определенной роли, перенаправляем на страницу выхода
      redirectToPage('Location: logout.php');
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

  // Генерация кода для подтверждения регистрации
  $confirmationCode = generateConfirmationCode();

  // Сохранение данных пользователя и кода подтверждения в базе данных
  $query = "INSERT INTO users (full_name, email, password, role, status, confirmation_code) VALUES ('$fullName', '$email', '$hashedPassword', 'student', 'inactive', '$confirmationCode')";
  mysqli_query($mysqli, $query);

  // Отправка письма с кодом для подтверждения регистрации
  function sendConfirmationEmail($email, $confirmationCode) {
    $subject = 'Подтверждение регистрации';
    $message = "Для подтверждения регистрации на сайте введите следующий код: $confirmationCode";
    $headers = "From: email@example.com"; // Укажите свой электронный адрес
  
    if (mail($email, $subject, $message, $headers)) {
      echo 'Письмо с кодом подтверждения отправлено на вашу электронную почту.';
    } else {
      echo 'Не удалось отправить письмо с кодом подтверждения.';
    }
  }
  
  // Отправка письма с кодом подтверждения
  sendConfirmationEmail($email, $confirmationCode);

  // Перенаправление на страницу с сообщением об успешной регистрации
  redirectToPage('Location: registration_success.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo escapeHTML('Страница регистрации'); ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo escapeHTML('Регистрация'); ?></h1>
        <p><?php echo escapeHTML('Заполните форму ниже для регистрации на сайте.'); ?></p>

        <div class="registration-form">
            <form action="<?php echo escapeHTML($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="full_name" placeholder="<?php echo escapeHTML('Полное имя'); ?>" required>
                <input type="email" name="email" placeholder="<?php echo escapeHTML('Электронная почта'); ?>" required>
                <input type="password" name="password" placeholder="<?php echo escapeHTML('Пароль'); ?>" required>
                <button type="submit"><?php echo escapeHTML('Зарегистрироваться'); ?></button>
            </form>
        </div>
    </div>
</body>
</html>
