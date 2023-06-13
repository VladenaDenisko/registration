<?php if (isset($_GET['edit'])) {
    include('secret_folder/db_conf.php');
		$my_id = $_GET['edit'];
		// Инъекция через параметры
        $query = $connection->prepare('SELECT * FROM my_user_table WHERE my_id=:my_id');
        $query->bindParam("my_id", $my_id, PDO::PARAM_STR);
        $query->execute();
		if($query->rowCount() > 0) {	
		$result = $query->fetch(PDO::FETCH_ASSOC);
 			
echo '<html>
    <head>
		<title>Редактирование записи</title>
		<meta charset="UTF-8">
    </head>
    <body>
		<h2 style="text-align: center;">Редактирование записи</h2>
		<form method="post" action="do_edit.php" align="right">
			<div style="padding-right:42%;">
				<input type="text" id="my_id" name="my_id" minlength="3" maxlength="100" value="' . $result['my_id'] .'" required hidden>
				<label for="my_login">Логин</label>
				<input type="text" id="my_login" name="my_login" minlength="3" maxlength="100" value="' . $result['my_login'] .'" required><br><br>
				<label for="my_old_password">Старый пароль</label>
				<input type="password" id="my_old_password" name="my_old_password" minlength="8" maxlength="100" required><br><br>
				<label for="my_password">Новый пароль</label>
				<input type="password" id="my_password" name="my_password" minlength="8" maxlength="100" required><br><br>
				<label for="my_password_confirm">Пароль повтор</label>
				<input type="password" id="my_password_confirm" name="my_password_confirm" minlength="8" maxlength="100" required><br><br>
				<label for="my_fio">ФИО</label>
				<input type="text" id="my_fio" name="my_fio" minlength="3" maxlength="250" value="' . $result['my_fio'] .'" required><br><br>
				<label for="my_email">E-mail</label>
				<input type="email" id="my_email" name="my_email" maxlength="250" value="' . $result['my_email'] .'" required><br><br>
				<label for="my_phone">Телефон</label>
				<input type="text" id="my_phone" name="my_phone" maxlength="250" value="' . $result['my_phone'] .'"><br><br>
				<label for="my_address">Адрес</label>
				<input type="text" id="my_address" name="my_address" maxlength="250" value="' . $result['my_address'] .'"><br><br>
				<label for="my_birthday">Дата рождения</label>
				<input type="date" id="my_birthday" name="my_birthday" value="' . $result['my_birthday'] .'"><br><br>
			</div>
			<div style="padding-right:42%;">
				<button type="submit" name="save" value="save">Сохранить</button>
			</div>			
		</form>
	</body>
</html>';
}
}
?>