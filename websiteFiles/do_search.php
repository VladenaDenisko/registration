<?php
if (isset($_POST['search'])) {
    include('secret_folder/db_conf.php');
		$my_fio = $_POST['my_fio'] . '%';
		// Инъекция через параметры
        $query = $connection->prepare('SELECT * FROM my_user_table WHERE my_fio LIKE :my_fio');
        $query->bindParam("my_fio", $my_fio, PDO::PARAM_STR);
        $query->execute();
	if($query->rowCount() > 0) {	
		echo '<form method="get" action="edit.php" align="center">';
		echo '<table style="margin-left:auto;margin-right:auto;border-spacing:15px;"><tr align="left"><th>Логин</th><th>ФИО</th><th>E-mail</th><th>Телефон</th><th>Адрес</th><th>Дата рождения</th><th></th></tr>';
		while($result = $query->fetch(PDO::FETCH_ASSOC)){ 
			echo '<tr>';
            echo '<td>' . $result['my_login'] . '</td>';
            echo '<td>' . $result['my_fio'] . '</td>';
			echo '<td>' . $result['my_email'] . '</td>';
			echo '<td>' . $result['my_phone'] . '</td>';
			echo '<td>' . $result['my_address'] . '</td>';
			echo '<td>' . $result['my_birthday'] . '</td>';
            echo '<td><button type="submit" name="edit" value="' . $result['my_id'] . '">Изменить</button></td>';
			echo '</tr>';
		}
		echo "</table>";
		echo '</form>';
		echo '<form action="index.php" align="center"><div><button type="submit">Сбросить</button></div></form>';
	} 	
}	
?>
