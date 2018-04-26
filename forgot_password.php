<?php
	include 'config/setup.php';
	$error = "none";
	if (!empty($_POST['submit']) && $_POST['submit'] === 'Send Reset Email')
	{
		if (!empty($_POST['email']))
		{
			$req = $db->prepare('SELECT * FROM users WHERE email = ?');
			$req->execute(array($_POST['email']));
			if ($req->rowCount() == 1)
			{
				// $result['confirm'] == '1'
				$result = $req->fetch(PDO::FETCH_ASSOC);

				$random = "";
				for ($i = 0; $i < 8; $i++)
					$random .=mt_rand(1, 9);
				$password = hash("whirlpool", $random);
				$req = $db->prepare("UPDATE users SET password=? WHERE ?=email");
				$req->execute(array($password, $_POST['email']));
				$header="MIME-Version: 1.0\r\n" . 'From:"Pic Cells"'."\n" . 'Content-Type:text/html; charset="uft-8"'."\n" . 'Content-Transfer-Encoding: 8bit';
				$msg = '
				<html><body><div align=center>
						Your Password has been reset, you can change it again once logged in. <BR />
						Your current username is: '. $result['login'] .' <BR />
						Your new password is: '. $random . ' <BR />
						<a href="http://localhost:8080/login.php"> Login! </a>
					</div></body></html>';
				mail($_POST['email'],"Password Reset",$msg, $header);
				$error = "Password has been successfully reset and e-mail has been sent";
			}
			else
				$error = "This e-mail address isn't linked to an existing account";
		}
		else
			$error = "Please input your e-mail address to reset your password";
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Reset Password</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<form method="post" action="forgot_password.php">
			<table class=box>
				<tr>
					<td>Reset account password</td>
				</tr>
				<tr>
					<td><p>Enter your account's E-mail: </p></td>
					<td><input type="email" name="email" value=""></td>
				</tr>
				<tr>
					<td><input type="submit" name="submit" value="Send Reset Email"></td>
				</tr>
			</table>
		</form>
		<?php if($error !== "none") { ?>
		<h1 class=error> <?php echo $error; ?></h1> <?php } ?>
		<?php include 'footer.php'; ?>
	</body>
</html>