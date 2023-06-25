<?php
session_start();
require_once 'db_connect.php';

// Обработка формы входа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Проверка учетных данных
  $sql = "SELECT * FROM Users WHERE Email = '$email' AND Password = '$password' AND Status = 'Активен'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);

    // Установка сессии для пользователя
    $_SESSION["user_id"] = $row["User ID"];
    $_SESSION["role"] = $row["Role"];

    // Перенаправление на главную страницу после успешного входа
    header("Location: index.php");
    exit();
  } else {
    $error_message = "Неверная электронная почта или пароль.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Вход на сайт</title>
  <link rel="stylesheet" type="text/css" href="styles.css"> <!-- Подключите ваш файл стилей (styles.css) -->
</head>
<body>
  <div class="container">
    <h1>Вход на сайт</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="email">Электронная почта:</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Войти">
      </div>
    </form>
    <?php if (isset($error_message)): ?>
      <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
  </div>
</body>
</html>
