<!DOCTYPE html>
<html>
<head>
    <title>Добро пожаловать</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Добро пожаловать!</h1>
    <?php
    session_start();

    // Проверяем, установлена ли сессия
    if (isset($_SESSION['user_email'])) {
        $email = $_SESSION['user_email'];
        echo "<p>Вы вошли как: $email</p>";
        echo '<a href="logout.php">Выйти</a>';
    } else {
        // Если сессия не установлена, перенаправляем на страницу входа
        header('Location: login.php');
        exit();
    }
    ?>
</div>
</body>
</html>
