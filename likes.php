<?php
	include 'config/setup.php';
	if (empty($_SESSION['login']))
	{
		header('Location: login.php?error=3');
		exit;
	}
	$img_id = intval(array_keys($_POST)[1]);
	$req = $db->prepare("SELECT * FROM likes where :id = img_id AND :user = user"); 
	$req->bindValue(':id', $img_id, PDO::PARAM_INT);
	$req->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	$req->execute();
	if ($req->rowCount() == '0')
	{
		$req = $db->prepare('INSERT INTO likes (user, img_id) VALUES (:user, :img_id)');
		$req->bindValue(':img_id', $img_id, PDO::PARAM_INT);
		$req->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
		$req->execute();
		
	}
	else
	{
		$req = $db->prepare("DELETE FROM likes WHERE user=:user AND img_id=:img_id");
		$req->bindValue(':img_id', $img_id, PDO::PARAM_INT);
		$req->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
		$req->execute();
	}
	header('Location: index.php');
	exit;
?>