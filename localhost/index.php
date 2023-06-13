<html>
    <head>
		<title>Главная страница</title>
		<meta charset="UTF-8">
    </head>
    <body>
		<h2 style="text-align: center;">Главная страница</h2>
		<form action="register.php" align="center">
			<div>
				<button type="submit">Новая регистрация</button>
			</div>			
		</form>
		
		<form method="post" action="index.php" align="center">
			<div>
				<label for="my_fio">Поиск по ФИО</label>
				<input type="text" id="my_fio" name="my_fio" required>
				<button type="submit" name="search" value="search">Поиск</button>
			</div>			
		</form>
<?php
include('do_search.php');
?>
	</body>
</html>