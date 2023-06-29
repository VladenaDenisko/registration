<?php
    // Проверка роли пользователя
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
        header('Location: index.php');
        exit();
    }

    // Подключение к базе данных
    require_once 'db_connect.php';

    // Получение данных преподавателя
    $teacher_id = $_SESSION['user_id'];

    // Запрос для получения имени преподавателя из базы данных
    $query = "SELECT full_name FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $teacher_id);
    $stmt->execute();
    $stmt->bind_result($teacher_name);
    $stmt->fetch();
    $stmt->close();

    // Проверка наличия данных преподавателя
    if (!$teacher_name) {
        // Обработка ситуации, когда данные преподавателя не найдены
        $teacher_name = 'Недоступно';
    }


    // Получение предметов, которые преподает преподаватель
    $query = "SELECT * FROM subjects WHERE id IN (SELECT subject_id FROM teacher_schedule WHERE teacher_id = ?) ORDER BY name";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $teacher_id);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Получение студентов, зарегистрированных на предметы преподавателя
    $query = "SELECT users.full_name AS student_name, subjects.name AS subject_name, student_schedule.subject_status 
              FROM users
              INNER JOIN student_schedule ON users.id = student_schedule.student_id
              INNER JOIN subjects ON student_schedule.subject_id = subjects.id
              WHERE student_schedule.subject_id IN (SELECT subject_id FROM teacher_schedule WHERE teacher_id = ?)
              ORDER BY student_name";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $teacher_id);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Страница преподавателя</title>
</head>
<body>
<div class="container">
    <h1 class="center">Добро пожаловать, <?php echo $teacher_name; ?></h1>
    <div class="table-container">
    <h2>Таблица предметов</h2>
    <table>
        <tr>
            <th>Название предмета</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($subjects as $subject) : ?>
            <tr>
                <td><?php echo $subject['name']; ?></td>
                <td><?php echo $subject['status']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                        <input type="hidden" name="subject_action" value="update_status">
                        <input type="submit" value="Изменить статус">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Таблица студентов</h2>
    <table>
        <tr>
            <th>ФИО студента</th>
            <th>Предмет</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($students as $student) : ?>
            <tr>
                <td><?php echo $student['student_name']; ?></td>
                <td><?php echo $student['subject_name']; ?></td>
                <td><?php echo $student['subject_status']; ?></td>
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
