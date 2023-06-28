<?php
// Подключение файла db_connection.php
require_once 'db_connection.php';

// Проверка роли пользователя
session_start();
 if ($_SESSION['role'] !== 'user') {
     header('Location: login.php'); // Перенаправление на страницу входа, если пользователь не является студентом
     exit();
}

function getSubjectsByStudent($studentId)
{
    $conn = getConnection();

    $sql = "SELECT subjects.* FROM subjects
            INNER JOIN subject_students ON subjects.id = subject_students.subject_id
            WHERE subject_students.student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $subjects;
}

// Получение ID студента из сессии
$studentId = $_SESSION['user_id'];

// Получение списка предметов студента
$subjects = getSubjectsByStudent($studentId);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Страница студента</title>
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
    </style>
</head>
<body>
<h1>Страница студента</h1>

<?php if (empty($subjects)): ?>
    <p>У вас нет доступных предметов.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Название предмета</th>
            <th>Описание предмета</th>
        </tr>
        <?php foreach ($subjects as $subject): ?>
            <tr>
                <td><?= $subject['name'] ?></td>
                <td><?= $subject['description'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
