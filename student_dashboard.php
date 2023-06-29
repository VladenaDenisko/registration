<?php
    // Проверка роли пользователя
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
        header('Location: index.php');
        exit();
    }

    // Подключение к базе данных
    require_once 'db_connect.php';

    // Получение данных студента
    $student_id = $_SESSION['user_id'];
    $query = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $stmt->bind_result($student_name);
    $stmt->fetch();
    $stmt->close();

    // Получение расписания предметов студента
    $query = "SELECT subjects.subject_name, subjects.subject_status 
              FROM subjects
              INNER JOIN student_subjects ON subjects.subject_id = student_subjects.subject_id
              WHERE student_subjects.student_id = :student_id
              ORDER BY subjects.subject_name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Страница студента</title>
</head>
<body>
<div class="container">
    <h1>Добро пожаловать, <?php echo $student_name; ?></h1>

    <h2>Расписание предметов</h2>
    <div class="table-container">
    <table>
        <tr>
            <th>Название предмета</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($schedule as $subject) : ?>
            <tr>
                <td><?php echo $subject['subject_name']; ?></td>
                <td><?php echo $subject['subject_status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    </div>
    <div class="logout-form">
    <form method="post" action="logout.php">
        <input type="submit" value="Выход из кабинета">
    </form>
    </div>
</div>
</body>
</html>
