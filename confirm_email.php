<?php
	include 'config/setup.php';
	if (isset($_GET['login'], $_GET['key']) && !empty($_GET['login'] && !empty($_GET['key'])))
	{
		$login = htmlspecialchars(urldecode($_GET['login']));
		$key = htmlspecialchars($_GET['key']);
		$req = $db->prepare("SELECT * FROM users WHERE login=? AND confirmkey=?");
		$req->execute(array($login, $key));
		if ($req->rowCount() == 1)
		{
			$req = $db->prepare("UPDATE users SET confirm='1' WHERE login=? AND confirmkey=?");
			$req->execute(array($login, $key));
			$_SESSION['login'] = $login;
			header("Location: index.php");
			exit;
		}
		else
			die ("Don't mess with my URLs, I'm protected ;)");
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Confirm E-mail</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<div class = box> Congratulations! You've almost joined Pic Cells. Please click on the link sent to you by e-mail to activate your account...</div>
		<?php include 'footer.php'; ?>
	</body>
</html>