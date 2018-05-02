<?php
	include 'config/setup.php';
	$error = "none";
	if (empty($_SESSION['login']))
	{
		header('Location: index.php');
		exit;
	}

	if (!empty($_POST['log']) && $_POST['log'] === 'Change Login')
	{
		if (!empty($_POST['login']))
		{
			$req = $db->prepare('SELECT login FROM users WHERE login = ?');
			$req->execute(array($_POST['login']));
			if ($req->rowCount() == 0)
			{
				$req = $db->prepare("UPDATE users SET login=? WHERE ?=login");
				$req->execute(array($_POST['login'], $_SESSION['login']));
				$req = $db->prepare("UPDATE images SET user=? WHERE ?=user");
				$req->execute(array($_POST['login'], $_SESSION['login']));
				$req = $db->prepare("UPDATE likes SET user=? WHERE ?=user");
				$req->execute(array($_POST['login'], $_SESSION['login']));
				$req = $db->prepare("UPDATE comments SET user=? WHERE ?=user");
				$req->execute(array($_POST['login'], $_SESSION['login']));
				$_SESSION['login'] = $_POST['login'];
				$error = "Login successfully changed";
			}
			else
				$error = "Error: This Username already exist. Please try again";
		}
		else
			$error = "Please input a new login to update";
	}

	if (!empty($_POST['pass']) && $_POST['pass'] === 'Change Password')
	{
		if (!empty($_POST['passwd']))
		{
			if (strlen($_POST['passwd']) < 8 || !preg_match("#[0-9]+#", $_POST['passwd']) || !preg_match("#[a-zA-Z]+#", $_POST['passwd']))
				$error = "your password must be at least 8 characters long and contain at least 1 number and 1 letter";
			else
			{
				$req = $db->prepare("UPDATE users SET password=? WHERE ?=login");
				$pass_hash = hash("whirlpool", $_POST['passwd']);
				$req->execute(array($pass_hash, $_SESSION['login']));
				$error = "Password successfully changed";
			}
		}
		else
			$error = "Please input a new password to update";
	}

	if (!empty($_POST['mail']) && $_POST['mail'] === 'Change E-mail')
	{
		if (!empty($_POST['email']))
		{
			$req = $db->prepare('SELECT email FROM users WHERE email = ?');
			$req->execute(array($_POST['email']));
			if ($req->rowCount() == 0)
			{
				if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				{
					$req = $db->prepare("UPDATE users SET email=? WHERE ?=login");
					$req->execute(array($_POST['email'], $_SESSION['login']));
					$error = "E-mail successfully changed";
				}
				else
					$error = "Please enter a valid e-mail to update";
			}
			else
				$error = "Error: This e-mail already exist. Please try again";
		}
		else
			$error = "Please input a new email to update";
	}

	if (!empty($_POST['notification']) && $_POST['notification'] === "Submit")
	{
		if (!empty($_POST['notif']))
		{
			if ($_POST['notif'] === 'yes')
			{
				$req = $db->prepare("UPDATE users SET notifications='1' WHERE ?=login");
				$req->execute(array($_SESSION['login']));
				$error = "You will now receive notifications";
			}
			else if ($_POST['notif'] === 'no')
			{
				$req = $db->prepare("UPDATE users SET notifications='0' WHERE ?=login");
				$req->execute(array($_SESSION['login']));
				$error = "You will no longer receive notifications";
			}
		}
		else
			$error = "Please select either yes or no to update notification status";
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Account Management</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		<?php include 'header.php' ?>
		<form method="post" action="manage_account.php">
			<table class=box>
				<tr>
					<td><p>Change Username: </p></td>
					<td><input type="text" name="login" value=""></td>
					<td><input type="submit" name="log" value="Change Login" class=button></td>
				</tr>
				<tr>
					<td>Change Password: </td>
					<td><input type="password" name="passwd" value=""></td>
					<td><input type="submit" name="pass" value="Change Password" class=button></td>
				</tr>
				<tr>
					<td>Change email: </td>
					<td><input type="email" name="email" value=""></td>
					<td><input type="submit" name="mail" value="Change E-mail" class=button></td>
				</tr>
				<tr>
					<td>E-mail Notifications: </td>
					<td><input type="radio" name="notif" value="yes">Yes
					<input type="radio" name="notif" value="no">No</td>
					<td><input type="submit" name="notification" value="Submit" class=button></td>
				</tr>
			</table>
		</form>
		<?php if($error !== "none") { ?>
		<h1 class=error> <?php echo $error; ?></h1> <?php } ?>
		<?php include 'footer.php'; ?>
	</body>
</html>