<?php
session_start();

// Подключение к базе данных
require_once 'db_connect.php';
require_once 'security.php';

// Проверка роли пользователя
if (!checkUserRole('admin')) {
    redirectToLogin();
}

// Функция для получения отсортированных записей из таблицы
function getSortedRecords($tableName, $sortField, $role = null)
{
    global $mysqli;

    $query = "SELECT * FROM $tableName";

    if ($role) {
        $query .= " WHERE role = '$role'";
    }

    $query .= " ORDER BY $sortField";

    $result = mysqli_query($mysqli, $query);

    $records = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }

    return $records;
}

// Получение отсортированных записей из таблицы 'subjects'
$subjects = getSortedRecords('subjects', 'name');

// Получение отсортированных записей из таблицы 'users' для преподавателей и студентов
$teachers = getSortedRecords('users', 'full_name', 'teacher');
$students = getSortedRecords('users', 'full_name', 'student');

// Обработка действий, связанных с таблицей 'subjects'
if (isset($_POST['subject_action'])) {
    $action = $_POST['subject_action'];

    if ($action === 'add') {
        $subjectName = escape($_POST['subject_name']);
        $quota = isset($_POST['quota']) ? (int) $_POST['quota'] : 0;

        $query = "INSERT INTO subjects (name, quota) VALUES ('$subjectName', $quota)";
        mysqli_query($mysqli, $query);
    } elseif ($action === 'update') {
        if (isset($_POST['subject_id'])) {
            $subjectId = (int) $_POST['subject_id'];
            $subjectName = escape($_POST['subject_name']);
            $quota = isset($_POST['quota']) ? (int) $_POST['quota'] : 0;

            $query = "UPDATE subjects SET name = '$subjectName', quota = $quota WHERE id = $subjectId";
            mysqli_query($mysqli, $query);
        }
    } elseif ($action === 'delete') {
        if (isset($_POST['subject_id'])) {
            $subjectId = (int) $_POST['subject_id'];

            $query = "DELETE FROM subjects WHERE id = $subjectId";
            mysqli_query($mysqli, $query);
        }
    }

    redirectToPage('admin_dashboard.php');
}


// Обработка действий, связанных с таблицей 'users' (преподаватели)
if (isset($_POST['teacher_action'])) {
    $action = $_POST['teacher_action'];

    if ($action === 'add') {
        $teacherName = $_POST['teacher_name'];
        $teacherEmail = $_POST['teacher_email'];

        // Генерация случайного пароля
        $randomPassword = generateRandomPassword();

        // Хеширование пароля
        $hashedPassword = password_hash($randomPassword, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (full_name, email, password, role, status) VALUES ('$teacherName', '$teacherEmail', '$hashedPassword', 'teacher', 'active')";
        mysqli_query($mysqli, $query);
        
        // Отправка пароля на почту преподавателя
        $subject = 'Добро пожаловать на сайт!';
        $message = "Ваш сгенерированный пароль: $randomPassword";
        
        if (sendEmail($teacherEmail, $subject, $message)) {
            echo 'Пароль отправлен на почту преподавателя.';
        } else {
            echo 'Не удалось отправить пароль на почту преподавателя.';
        }

    } elseif ($action === 'update') {
        if (isset($_POST['teacher_id'])) {
            $teacherId = $_POST['teacher_id'];
            $teacherName = $_POST['teacher_name'];

            $query = "UPDATE users SET full_name = '$teacherName' WHERE id = $teacherId";
            mysqli_query($mysqli, $query);
        }
    } elseif ($action === 'delete') {
        if (isset($_POST['teacher_id'])) {
            $teacherId = $_POST['teacher_id'];

            $query = "DELETE FROM users WHERE id = $teacherId";
            mysqli_query($mysqli, $query);
        }
    }

    redirectToPage('admin_dashboard.php');
}

// Обработка действий, связанных с таблицей 'users' (студенты)
if (isset($_POST['student_action'])) {
    $action = $_POST['student_action'];
    $studentId = $_POST['student_id'];

    if ($action === 'update_status') {
        $status = $_POST['student_status'];

        $query = "UPDATE users SET status = '$status' WHERE id = $studentId";
        mysqli_query($mysqli, $query);
    } elseif ($action === 'update_password') {
        $password = $_POST['student_password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$hashedPassword' WHERE id = $studentId";
        mysqli_query($mysqli, $query);
    }

    redirectToPage('admin_dashboard.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo escapeHTML('Панель администратора'); ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, <?php echo escapeHTML('Администратор'); ?>!</h1>
        <div class="table-container">
            <h2>Управление предметами</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Название предмета</th>
                    <th>Квота</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($subjects as $subject) : ?>
                    <tr>
                        <td><?php echo escapeHTML($subject['id']); ?></td>
                        <td><?php echo escapeHTML($subject['name']); ?></td>
                        <td><?php echo escapeHTML($subject['quota']); ?></td>
                        <td class="btn-row">
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="subject_id" value="<?php echo escapeHTML($subject['id']); ?>">
                                <input type="hidden" name="subject_name" value="<?php echo escapeHTML($subject['name']); ?>">
                                <input type="hidden" name="quota" value="<?php echo escapeHTML($subject['quota']); ?>">
                                <input type="hidden" name="subject_action" value="update">
                                <input type="submit" value="Обновить">
                            </form>
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="subject_id" value="<?php echo escapeHTML($subject['id']); ?>">
                                <input type="hidden" name="subject_action" value="delete">
                                <input type="submit" value="Удалить">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <form method="post" action="admin_dashboard.php">
                        <td><input type="hidden" name="subject_action" value="add"></td>
                        <td><input type="text" name="subject_name" required></td>
                        <td><input type="number" name="quota" required></td>
                        <td><input type="submit" value="Добавить"></td>
                    </form>
                </tr>
            </table>
            <br>
            <h2>Управление преподавателями</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Имя преподавателя</th>
                    <th>Почта</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($teachers as $teacher) : ?>
                    <tr>
                        <td><?php echo escapeHTML($teacher['id']); ?></td>
                        <td><?php echo escapeHTML($teacher['full_name']); ?></td>
                        <td><?php echo escapeHTML($teacher['email']); ?></td>
                        <td class="btn-row">
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="teacher_id" value="<?php echo escapeHTML($teacher['id']); ?>">
                                <input type="hidden" name="teacher_action" value="update">
                                <input type="submit" value="Обновить">
                            </form>
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="teacher_id" value="<?php echo escapeHTML($teacher['id']); ?>">
                                <input type="hidden" name="teacher_action" value="delete">
                                <input type="submit" value="Удалить">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <form method="post" action="admin_dashboard.php">
                        <td><input type="hidden" name="teacher_action" value="add"></td>
                        <td><input type="text" name="teacher_name" required></td>
                        <td><input type="email" name="teacher_email" required></td>
                        <td><input type="submit" value="Добавить"></td>
                    </form>
                </tr>
            </table>
            <br>
            <h2>Управление студентами</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Имя студента</th>
                    <th>Почта</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($students as $student) : ?>
                    <tr>
                        <td><?php echo escapeHTML($student['id']); ?></td>
                        <td><?php echo escapeHTML($student['full_name']); ?></td>
                        <td><?php echo escapeHTML($student['email']); ?></td>
                        <td><?php echo escapeHTML($student['status']); ?></td>
                        <td class="btn-row">
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="student_id" value="<?php echo escapeHTML($student['id']); ?>">
                                <input type="hidden" name="student_status" value="<?php echo escapeHTML($student['status']); ?>">
                                <input type="hidden" name="student_action" value="update_status">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="active" <?php if ($student['status'] === 'active') echo 'selected'; ?>>Активен</option>
                                    <option value="inactive" <?php if ($student['status'] === 'inactive') echo 'selected'; ?>>Неактивен</option>
                                </select>
                            </form>
                            <form method="post" action="admin_dashboard.php">
                                <input type="hidden" name="student_id" value="<?php echo escapeHTML($student['id']); ?>">
                                <input type="hidden" name="student_action" value="update_password">
                                <input type="password" name="student_password" placeholder="Новый пароль" required>
                                <input type="submit" value="Изменить пароль">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br>
        <a href="logout.php" class="logout-link">Выйти</a>
    </div>
</body>
</html>