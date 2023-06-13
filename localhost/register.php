<html>
    <head>
		<title>Новая регистрация</title>
		<meta charset="UTF-8">
    </head>
    <body>
		<h2 style="text-align: center;">Новая регистрация</h2>
		<form method="post" action="do_register.php" align="right">
			<div style="padding-right:42%;">
				<label for="my_login">Логин</label>
				<input type="text" id="my_login" name="my_login" minlength="3" maxlength="100" required><br><br>
				<label for="my_password">Пароль</label>
				<input type="password" id="my_password" name="my_password" minlength="8" maxlength="100" required><br><br>
				<label for="my_password_confirm">Пароль повтор</label>
				<input type="password" id="my_password_confirm" name="my_password_confirm" minlength="8" maxlength="100" required><br><br>
				<label for="my_fio">ФИО</label>
				<input type="text" id="my_fio" name="my_fio" minlength="3" maxlength="250" required><br><br>
				<label for="my_email">E-mail</label>
				<input type="email" id="my_email" name="my_email" maxlength="250" required><br><br>
				<label for="my_phone">Телефон</label>
				<input type="text" id="my_phone" name="my_phone" maxlength="250"><br><br>
				<label for="my_address">Адрес</label>
				<input type="text" id="my_address" name="my_address" maxlength="250"><br><br>
				<label for="my_birthday">Дата рождения</label>
				<input type="date" id="my_birthday" name="my_birthday"><br><br>
			</div>
			<div style="padding-right:42%;">
				<button type="submit" name="register" value="register">Зарегистрировать</button>
			</div>			
		</form>
	</body>
</html>