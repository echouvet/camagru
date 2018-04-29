<?php
	include 'config/setup.php';
	$error = "none";
	if ($_GET['error'] === '1')
		$error = "Please log in create Pic Cells";
	if ($_GET['error'] === '2')
		$error = "Please log in to comment on a Pic Cell";
	if (!empty($_POST['forgot']) && $_POST['forgot'] === 'Forgot your Password?')
	{
		header("Location: forgot_password.php");
		exit;
	}
	if (!empty($_POST['confirm']) && $_POST['confirm'] === 'Login')
	{
		if ($_POST['login'] && $_POST['passwd'])
		{
			$pass_hash = hash("whirlpool", $_POST['passwd']);
			$req = $db->prepare('SELECT * FROM users WHERE login = ? AND password = ?');
			$req->execute(array($_POST['login'], $pass_hash));
			if ($req->rowCount() == 1)
			{
				$result = $req->fetch(PDO::FETCH_ASSOC);
				if ($result['confirm'] == '1')
				{
					$_SESSION['login'] = $_POST['login'];
					header("Location: index.php");
					exit;
				}
				else
					$error = "Please activate your account using the link sent to you by e-mail during registration";
			}
			else
				$error = "Username or Password don't exist or don't match";
		}
		else
			$error = "Error: Please input your Username and Password";
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<form method="post" action="login.php">
			<table class=box>
				<tr>
					<td><p>Username: </p></td>
					<td><input type="text" name="login" value=""></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td><input type="password" name="passwd" value=""></td>
				</tr>
				<tr>
					<td><input type="submit" name="confirm" value="Login" class=button></td>
					<td><input type="submit" name="forgot" value="Forgot your Password?" class=button></td>
				</tr>
			</table>
		</form>
		<?php if($error !== "none") { ?>
		<h1 class=error> <?php echo $error; ?></h1> <?php } ?>
		<?php include 'footer.php'; ?>
	</body>
</html>