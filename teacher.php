<?php
// Подключение файла db_connection.php
require_once 'db_connection.php';

// Проверка роли пользователя
session_start();
 if ($_SESSION['role'] !== 'teacher') {
     header('Location: login.php'); // Перенаправление на страницу входа, если пользователь не является преподавателем
     exit();
}

function getSubjectsByTeacher($teacherId)
{
    $conn = getConnection();

    $sql = "SELECT subjects.* FROM subjects
            INNER JOIN users ON users.id = subjects.teacher_id
            WHERE subjects.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $teacherId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $subjects;
}

function getStudentsBySubject($subjectId)
{
    $conn = getConnection();

    $sql = "SELECT users.* FROM subjects
            INNER JOIN subject_students ON subjects.id = subject_students.subject_id
            INNER JOIN users ON subject_students.student_id = users.id
            WHERE subject_students.subject_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $subjectId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $students;
}

function addSubject($teacherId, $name, $description)
{
    $conn = getConnection();

    $sql = "INSERT INTO subjects (teacher_id, name, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $teacherId, $name, $description);
    $success = mysqli_stmt_execute($stmt);
    mysqli_commit($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $success;
}

function addStudentToSubject($studentId, $subjectId)
{
    $conn = getConnection();

    $sql = "INSERT INTO subject_students (student_id, subject_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $studentId, $subjectId);
    $success = mysqli_stmt_execute($stmt);
    mysqli_commit($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $success;
}

// Обработка отправки формы добавления предмета
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Добавление предмета
    if (isset($_POST['add_subject'])) {
        $teacherId = $_SESSION['user_id'];
        $name = $_POST['subject_name'];
        $description = $_POST['subject_description'];

        // Вызов функции для добавления предмета
        $success = addSubject($teacherId, $name, $description);
        if ($success) {
            echo "Предмет успешно добавлен!";
        } else {
            echo "Ошибка при добавлении предмета";
        }
    }

    // Добавление студента к предмету
    if (isset($_POST['add_student'])) {
        $studentId = $_POST['student_id'];
        $subjectId = $_POST['subject_id'];

        // Вызов функции для добавления связки студент-предмет
        $success = addStudentToSubject($studentId, $subjectId);
        if ($success) {
            echo "Студент успешно добавлен к предмету!";
        } else {
            echo "Ошибка при добавлении студента к предмету";
        }
    }
}

// Получение ID преподавателя из сессии
$teacherId = $_SESSION['user_id'];

// Получение списка предметов, которые преподаватель ведет
$subjects = getSubjectsByTeacher($teacherId);

// Получение списка студентов из базы данных
$conn = getConnection();
$sql = "SELECT * FROM users WHERE role = 'user'";
$result = mysqli_query($conn, $sql);
$students = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Страница преподавателя</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        p {
            color: #666;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button[type="submit"] {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<h1>Страница преподавателя</h1>

<?php if (empty($subjects)): ?>
    <p>Нет доступных предметов.</p>
<?php else: ?>
    <?php foreach ($subjects as $subject): ?>
        <h2><?= $subject['name'] ?></h2>

        <?php
        // Получение списка студентов, зарегистрированных на предмет
        $studentsData = getStudentsBySubject($subject['id']);
        ?>

        <?php if (empty($studentsData)): ?>
            <p>На данный предмет нет зарегистрированных студентов.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Действия</th>
                </tr>
                <?php foreach ($studentsData as $student): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= $student['role'] ?></td>
                        <td><?= $student['email'] ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                                <button type="submit" name="add_student">Добавить студента</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<h2>Добавить предмет</h2>
<form method="POST" action="">
    <label for="subject_name">Название предмета:</label>
    <input type="text" name="subject_name" id="subject_name" required><br>
    <label for="subject_description">Описание предмета:</label>
    <textarea name="subject_description" id="subject_description" required></textarea><br>
    <button type="submit" name="add_subject">Добавить предмет</button>
</form>

<h2>Добавить студента к предмету</h2>
<form method="POST" action="">
    <label for="student_id">Выберите студента:</label>
    <select name="student_id" id="student_id" required>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"><?= $student['id'] ?> - <?= $student['email'] ?></option>
        <?php endforeach; ?>
    </select>
    <label for="subject_id">Выберите предмет:</label>
    <select name="subject_id" id="subject_id" required>
        <?php foreach ($subjects as $subject): ?>
            <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="add_student">Добавить студента к предмету</button>
</form>
</body>
</html>
