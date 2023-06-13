<?php
    include('secret_folder/db_conf.php');
    if (isset($_POST['save'])) {
		$my_id=$_POST['my_id'];
        $my_login = $_POST['my_login'];
		$my_old_password = $_POST['my_old_password'];
        $my_password = $_POST['my_password'];
		$my_password_confirm = $_POST['my_password_confirm'];
        $my_password_hash = password_hash($my_password, PASSWORD_DEFAULT);
		$my_fio = $_POST['my_fio'];
		$my_email = $_POST['my_email'];
		$my_phone = $_POST['my_phone'];
		$my_address = $_POST['my_address'];
		$my_birthday = $_POST['my_birthday'];
		// Инъекция через параметры
        $query = $connection->prepare("SELECT * FROM my_user_table WHERE my_login=:my_login AND my_id!=:my_id");
        $query->bindParam("my_login", $my_login, PDO::PARAM_STR);
		$query->bindParam("my_id", $my_id, PDO::PARAM_STR);
		$query->execute();
		// Инъекция через параметры
		$query2 = $connection->prepare("SELECT * FROM my_user_table WHERE my_id=:my_id");
        $query2->bindParam("my_id", $my_id, PDO::PARAM_STR);
        $query2->execute();
        $result = $query2->fetch(PDO::FETCH_ASSOC);
		// Инъекция через параметры
		$query3 = $connection->prepare("SELECT * FROM my_user_table WHERE my_email=:my_email AND my_id!=:my_id");
        $query3->bindParam("my_email", $my_email, PDO::PARAM_STR);
		$query3->bindParam("my_id", $my_id, PDO::PARAM_STR);
		$query3->execute();
		if ($query->rowCount() > 0) {
			echo '<html>
				<head>
					<title>Ошибка</title>
					<meta charset="UTF-8">
				</head>
				<body>
					<h2 style="text-align: center;">Этот логин уже зарегистрирован!</h2>
					<form align="center">
						<div>
							<input type="button" value="Назад" onclick="history.back()">
						</div>			
					</form>
				</body>
			</html>';
		}
		elseif (password_verify($my_old_password, $result['password'])) {
			echo '<html>
				<head>
					<title>Ошибка</title>
					<meta charset="UTF-8">
				</head>
				<body>
					<h2 style="text-align: center;">Старый пароль неверный!</h2>
					<form align="center">
						<div>
							<input type="button" value="Назад" onclick="history.back()">
						</div>			
					</form>
				</body>
			</html>';
		}			
		elseif ($my_password!=$my_password_confirm) {
				echo '<html>
					<head>
						<title>Ошибка</title>
						<meta charset="UTF-8">
					</head>
					<body>
						<h2 style="text-align: center;">Повторный пароль не совпадает!</h2>
						<form align="center">
							<div>
								<input type="button" value="Назад" onclick="history.back()">
							</div>			
						</form>
					</body>
				</html>';
        }
        elseif ($query3->rowCount() > 0) {
            echo '<html>
					<head>
						<title>Ошибка</title>
						<meta charset="UTF-8">
					</head>
					<body>
						<h2 style="text-align: center;">Этот E-mail уже зарегистрирован!</h2>
						<form align="center">
							<div>
								<input type="button" value="Назад" onclick="history.back()">
							</div>			
						</form>
					</body>
				</html>';
        } 
		else {
			// Инъекция через параметры
            $query = $connection->prepare("UPDATE my_user_table SET my_login=:my_login,my_password=:my_password,my_fio=:my_fio,my_email=:my_email,my_phone=:my_phone,my_address=:my_address,my_birthday=:my_birthday WHERE my_id=:my_id");
            $query->bindParam("my_id", $my_id, PDO::PARAM_STR);
			$query->bindParam("my_login", $my_login, PDO::PARAM_STR);
            $query->bindParam("my_password", $my_password_hash, PDO::PARAM_STR);
            $query->bindParam("my_fio", $my_fio, PDO::PARAM_STR);
			$query->bindParam("my_email", $my_email, PDO::PARAM_STR);
			$query->bindParam("my_phone", $my_phone, PDO::PARAM_STR);
			$query->bindParam("my_address", $my_address, PDO::PARAM_STR);
			$query->bindParam("my_birthday", $my_birthday, PDO::PARAM_STR);
            $result = $query->execute();
            if ($result) {
                echo '<html>
					<head>
						<title>Успех</title>
						<meta charset="UTF-8">
					</head>
					<body>
						<h2 style="text-align: center;">Успешное сохранение!</h2>
						<form action="index.php" align="center">
							<div>
								<button type="submit">На Главную</button>
							</div>			
						</form>
					</body>
				</html>';
            } else {
                '<html>
					<head>
						<title>Ошибка</title>
						<meta charset="UTF-8">
					</head>
					<body>
						<h2 style="text-align: center;">Неверные данные!</h2>
						<form align="center">
							<div>
								<input type="button" value="Назад" onclick="history.back()">
							</div>			
						</form>
					</body>
				</html>';
            }
        }
    }
?>
