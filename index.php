<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
if (isset($_GET['logout'])) {
	session_destroy();
	header("location:index.php");
}

$pdo = new PDO("mysql:host=localhost;dbname=todo; charset=utf8", "root","");

if (isset($_GET['del'],$_GET['id'])) {
	$stmt = $pdo->prepare("DELETE FROM task WHERE user_id=".$_SESSION['user_id']."  AND id=".$_GET['id']." LIMIT 1");
	$stmt->execute();
	$del = $stmt->fetchAll(PDO::FETCH_ASSOC);
	header("location:index.php");
}
if (isset($_POST['task_id'],$_POST['assigned_user_id'])) {
	$stmt = $pdo->prepare("UPDATE task SET assigned_user_id=".$_POST['assigned_user_id']." WHERE id=".$_POST['task_id']."");
	$stmt->execute();
	$upd = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_GET['id'],$_GET['done'])) {
	if ($_GET['done']==1) {
			$stmt = $pdo->prepare("UPDATE task SET is_done= 0 WHERE user_id= ".$_SESSION['user_id']." AND id= ".$_GET['id']." LIMIT 1");
		$stmt->execute();
		$update = $stmt->fetchAll(PDO::FETCH_ASSOC);
		header("location:index.php");
	}
	elseif ($_GET['done']==0) {
		$stmt = $pdo->prepare("UPDATE task SET is_done= 1 WHERE user_id= ".$_SESSION['user_id']." AND id= ".$_GET['id']." LIMIT 1");
		$stmt->execute();
		$update = $stmt->fetchAll(PDO::FETCH_ASSOC);
		header("location:index.php");
	}
}
if (isset($_POST['name'],$_POST['password'])) {
	$stmt = $pdo->prepare('SELECT id FROM user WHERE login= ?');
	$stmt->execute([$_POST['name']]);
	$id = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (!empty($id)) {
		$stmt = $pdo->prepare('SELECT id FROM user WHERE login= ? AND password= ?');
		$stmt->execute([$_POST['name'],$_POST['password']]);
		$id = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (empty($id)) {
		exit("Ошибка! Неверный пароль");
		}
		foreach ($id as $key => $value) {
			$_SESSION['user_id'] = $value['id'];
		}
	}
	else {
		$stmt = $pdo->prepare("INSERT INTO user (login, password) VALUES ('".$_POST['name']."','".$_POST['password']."')");
		$stmt->execute();
		$registr = $stmt->fetchAll(PDO::FETCH_ASSOC);
		echo "Вы успешно зарегистрированы";
	}
}
if (isset($_POST['description']) && !empty($_POST['description'])) {
	if (!empty($_POST['date'])) {
		$stmt = $pdo->prepare("INSERT INTO task (user_id,assigned_user_id,description,date_added) VALUES (:user_id,:assigned_user_id,:description,:date_added)");
		$stmt->execute(["user_id"=>$_SESSION['user_id'],"assigned_user_id"=>$_SESSION['user_id'],"description"=>$_POST['description'],"date_added"=>$_POST['date']]);
		$description = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	else {
		$stmt = $pdo->prepare("INSERT INTO task (user_id,assigned_user_id,description) VALUES (:user_id,:assigned_user_id,:description)");
		$stmt->execute(["user_id"=>$_SESSION['user_id'],"assigned_user_id"=>$_SESSION['user_id'],"description"=>$_POST['description']]);
		$description = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT description, DATE_FORMAT(date_added, \"%d.%m.%Y\") as date_added FROM task WHERE user_id = ".$_SESSION['user_id']." ORDER BY YEAR(date_added) ASC, MONTH(date_added) ASC,DAY(date_added) ASC");
	$stmt->execute();
	$tableOne = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT id, description, DATE_FORMAT(date_added, \"%d.%m.%Y\") as date_added FROM task WHERE user_id = ".$_SESSION['user_id']." ORDER BY YEAR(date_added) ASC, MONTH(date_added) ASC,DAY(date_added) ASC");
	$stmt->execute();
	$tableTwo = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT id, description, DATE_FORMAT(date_added, \"%d.%m.%Y\") as date_added,is_done FROM task WHERE user_id = ".$_SESSION['user_id']." ORDER BY YEAR(date_added) ASC, MONTH(date_added) ASC,DAY(date_added) ASC");
	$stmt->execute();
	$tableThree = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT id,login FROM user");
	$stmt->execute();
	$assignedUserList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT assigned_user_id FROM task");
	$stmt->execute();
	$task = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT assigned_user_id,id, description, DATE_FORMAT(date_added, \"%d.%m.%Y\") as date_added,is_done FROM task WHERE user_id = ".$_SESSION['user_id']." ORDER BY YEAR(date_added) ASC, MONTH(date_added) ASC,DAY(date_added) ASC");
	$stmt->execute();
	$tableFour = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT t.id as id,assigned_user_id,description, DATE_FORMAT(date_added, \"%d.%m.%Y\") as date_added, is_done FROM task t INNER JOIN user u ON u.id=t.assigned_user_id WHERE t.user_id = ".$_SESSION['user_id']." OR t.assigned_user_id = ".$_SESSION['user_id']." ORDER BY YEAR(date_added) ASC, MONTH(date_added) ASC,DAY(date_added) ASC");
	$stmt->execute();
	$tableFive = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
if (isset($_SESSION['user_id'])) {
	$stmt = $pdo->prepare("SELECT count(*) FROM task t WHERE t.user_id = ".$_SESSION['user_id']." OR t.assigned_user_id = ".$_SESSION['user_id']."");
	$stmt->execute();
	$tableSix = $stmt->fetch(PDO::FETCH_ASSOC);
}

 ?>
 <!doctype html>
 <html lang="ru">
 <head>
 	<meta charset="UTF-8">
 	<title>Document</title>
 </head>
 <body>
 	<?php if (!isset($_SESSION['user_id'])) {  ?>	
 	<h2>Авторизуйтесь или зарегистрируйтесь</h2>
	<form action="" method="POST">
 		<p><label for="name">Введите логин </label><input type="text" name="name" id="name" required></p>
 		<p><label for="password">Введите пароль </label><input type="password" name="password" id="password" required></p>
 		<p><input type="submit" value="Войти/Зарегистрироваться"></p>
 	</form>
 	 <?php } ?>
 	<?php if (isset($_SESSION['user_id'])) {  ?>	
		<p>Привет, <?php echo $_SESSION['user_id']; ?>!</p>
		<p>Кол-во ваших дел = <?=$tableSix['count(*)'] ?></p>
		<a href="index.php?logout=true">Выйти</a>
		<form action="" method="POST">
			<p>Добавление нового вашего дела</p>
			<p><label for="description">Описание </label><input type="text" name="description" id="description"></p>
			<p><label for="date">Дата </label><input type="date" name="date" id="date"></p>
			<p><input type="submit"></p>
		</form>
		<p>Таблица №1</p>
		<table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
			</thead>
			<?php foreach ($tableOne as $row) { ?>
				<tr>
					<td><?=$row['description'] ?></td>
					<td><?=$row['date_added'] ?></td>
				</tr>
			<?php } ?>
 		</table>
 		<p>Таблица №2</p>
 		<table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
				<th>Удалить</th>
			</thead>
			<?php foreach ($tableTwo as $row) { ?>
				<tr>
					<td><?=$row['description'] ?></td>
					<td><?=$row['date_added'] ?></td>
					<td><a href="index.php?del=true&id=<?=$row['id'] ?>">удалить</a></td>
				</tr>
			<?php } ?>
 		</table>		
 		<p>Таблица №3</p>
 		<table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
				<th>Выполнено/Невыполнено</th>
				<th>Удалить</th>
			</thead>
			<?php foreach ($tableThree as $row) { 
						if ($row['is_done']==1) {
							$done = 'Выполнено';
						}
						elseif ($row['is_done']==0) {
						$done = 'Невыполнено';
			}?>
				<tr>
					<td><?=$row['description'] ?></td>
					<td><?=$row['date_added'] ?></td>
					<td><a href="index.php?id=<?=$row['id'] ?>&done=<?=$row['is_done'] ?>"><?=$done ?></a></td>
					<td><a href="index.php?del=true&id=<?=$row['id'] ?>">удалить</a></td>
				</tr>
			<?php } ?>
 		</table>		
		 <table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
				<th>Выполнено/Невыполнено</th>
				<th>Исполнитель</th>
			</thead>
 		</table>
 		 		<p>Таблица №4</p>
 		<table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
				<th>Выполнено/Невыполнено</th>
				<th>Исполнитель</th>
				<th>Удалить</th>
			</thead>
			<?php foreach ($tableFour as $row) { 
						if ($row['is_done']==1) {
							$done = 'Выполнено';
						}
						elseif ($row['is_done']==0) {
						$done = 'Невыполнено';
			}?>
				<tr>
					<td><?=$row['description'] ?></td>
					<td><?=$row['date_added'] ?></td>
					<td><a href="index.php?id=<?=$row['id'] ?>&done=<?=$row['is_done'] ?>"><?=$done ?></a></td>
					<th>
						<form action="index.php" method="POST">
						<input name="task_id" type="hidden" value="<?=$row['id'] ?>"> 
						<select name="assigned_user_id">
						<?php foreach ($assignedUserList as $assignedUser): ?>
						  <option <?php if ($row['assigned_user_id'] == $assignedUser['id']):?> selected<?php endif; ?> value="<?= $assignedUser['id'] ?>">
						    <?= $assignedUser['login'] ?>
						  </option>
						<?php endforeach; ?>
						</select>
						<button type="submit">Делегировать</button>
						</form>
					</th>
					<td><a href="index.php?del=true&id=<?=$row['id'] ?>">удалить</a></td>
				</tr>
			<?php } ?>
 		</table>		
 		 		<p>Таблица №5</p>
 		<table>
	 		<thead>
				<th>Дела</th>
				<th>Когда</th>
				<th>Выполнено/Невыполнено</th>
				<th>Исполнитель</th>
				<th>Удалить</th>
			</thead>
			<?php foreach ($tableFive as $row) { 
						if ($row['is_done']==1) {
							$done = 'Выполнено';
						}
						elseif ($row['is_done']==0) {
						$done = 'Невыполнено';
			}?>
				<tr>
					<td><?=$row['description'] ?></td>
					<td><?=$row['date_added'] ?></td>
					<td><a href="index.php?id=<?=$row['id'] ?>&done=<?=$row['is_done'] ?>"><?=$done ?></a></td>
					<th>
						<form action="index.php" method="POST">
						<input name="task_id" type="hidden" value="<?=$row['id'] ?>"> 
						<select name="assigned_user_id">
						<?php foreach ($assignedUserList as $assignedUser): ?>
						  <option <?php if ($row['assigned_user_id'] == $assignedUser['id']):?> selected<?php endif; ?> value="<?= $assignedUser['id'] ?>">
						    <?= $assignedUser['login'] ?>
						  </option>
						<?php endforeach; ?>
						</select>
						<button type="submit">Делегировать</button>
						</form>
					</th>
					<td><a href="index.php?del=true&id=<?=$row['id'] ?>">удалить</a></td>
				</tr>
			<?php } ?>
 		</table>
 		
	<?php } ?>
	

 </body>
 </html>
