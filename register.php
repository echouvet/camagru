<?php
include 'config/setup.php';
$error = "none";
if (!empty($_POST['submit']) && $_POST['submit'] === 'Create my account')
{
	if ($_POST['login'] && $_POST['passwd'] && $_POST['passwd2'] && $_POST['email'])
	{
		if ($_POST['passwd'] !== $_POST['passwd2'])
			$error = "Error: Your password confirmation did not match your initial password. Please try again";
		else 
		{
			$req = $db->prepare('SELECT login FROM users WHERE login = ? OR email = ?');
			$req->execute(array($_POST['login'], $_POST['email']));
			if ($req->rowCount() != 0)
				$error = "Error: This Username or E-mail already exist. Please try again";
			else
			{
				if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
					$error = "Please input a valid e-mail";
				else if (strlen($_POST['passwd']) < 8 || !preg_match("#[0-9]+#", $_POST['passwd']) || !preg_match("#[a-zA-Z]+#", $_POST['passwd']))
					$error = "Error: Your password must be at least 8 characters long and contain at least 1 number and 1 letter";
				else 
				{
					$password = hash("whirlpool", $_POST['passwd']);
					$key = "";
					for ($i = 0; $i < 4; $i++)
						$key .=mt_rand(1, 9);
					$req = $db->prepare('INSERT INTO users (login, email, password, confirmkey) VALUES (?, ?, ?, ?)');
					$req->execute(array($_POST['login'], $_POST['email'], $password, $key));
					$header="MIME-Version: 1.0\r\n" . 'From:"Pic Cells"'."\n" . 'Content-Type:text/html; charset="uft-8"'."\n" . 'Content-Transfer-Encoding: 8bit';
					$msg = '
					<html><body><div align=center>
							PLEASE CLICK ON THE FOLLOWING LINK TO VALIDATE YOUR ACCOUNT: <BR />
							<a href="http://localhost:8080/confirm_email.php?login=' . urlencode($_POST['login']).'&key='.$key.'"> Confirm your Account ! </a>
						</div></body></html>';
					mail($_POST['email'],"E-mail Confirmation",$msg, $header);
					header("Location: /confirm_email.php");
					exit;
				}
			}
		}
	}
	else
		$error = "Error: Please fill in all four fields to register";
}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		<?php include 'header.php' ?> 
		<form method="post" action="register.php">
			<table class=box>
				<tr>
					<td><p>Username: </p></td>
					<td><input type="text" name="login" value="" required></td>
				</tr>
				<tr>
					<td>Password: </td>
					<td><input type="password" name="passwd" value="" required></td>
				</tr>
				<tr>
					<td>Confirm Password: </td>
					<td><input type="password" name="passwd2" value="" required></td>
				</tr>
				<tr>
					<td>E-mail Address: </td>
					<td><input type="email" name="email" value="" required></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit" value="Create my account" class=button></td>
				</tr>
			</table>
		</form>
		<?php if($error !== "none") { ?>
		<h1 class=error> <?php echo $error; ?></h1> <?php } ?>
		<?php include 'footer.php'; ?>
	</body>
</html>
