<?php
	session_start();
	include 'database.php';
	try
	{
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("SET NAMES 'UTF8'");
		
		$db->query("CREATE DATABASE IF NOT EXISTS `camagru`");
		$db->query("USE camagru");
		
		$db->query("CREATE TABLE IF NOT EXISTS
		`camagru`.`users`(
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
		`login` TEXT NOT NULL,
		`email` TEXT NOT NULL,
		`password` TEXT NOT NULL,
		`confirmkey` INT NOT NULL,
		`confirm` INT NOT NULL DEFAULT '0',
		`notifications` INT NOT NULL DEFAULT '1')");

		$db->query("CREATE TABLE IF NOT EXISTS
		`camagru`.`images`(
		`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
		`creation_date` TIMESTAMP NOT NULL,
		`user` TEXT NOT NULL,
		`img` TEXT NOT NULL)");

		$db->query("CREATE TABLE IF NOT EXISTS
		`camagru`.`likes`(
		`user` TEXT NOT NULL,
		`img_id` INT NOT NULL)");

		$db->query("CREATE TABLE IF NOT EXISTS
		`camagru`.`comments`(
		`img_id` INT NOT NULL,
		`user` TEXT NOT NULL,
		`comment` TEXT NOT NULL)");
	}
	catch (Exception $e)
	{exit($e->getMessage());}

	if ($db->connect_error)
		die("Connection failed: " . $db->connect_error);
	if ($db->error)
		die("ERROR: " . $db->error);
?>
