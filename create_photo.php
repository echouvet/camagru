<?php
include 'config/setup.php';

if ($_POST && isset($_POST['photo']))
{
	if (isset($_SESSION['login']))
	{
		if (isset($_POST['img']) && $_POST['img'] !== 'none')
		{
			$img = explode(',', $_POST['photo'], 2)[1];
			$img = base64_decode($img);
			if (file_put_contents("tmp.png", $img) == TRUE)
			{
				$image_1 = imagecreatefrompng('tmp.png');
				$image_2 = imagecreatefrompng($_POST['img']);
				imagecopy($image_1, $image_2, 0, 0, 0, 0, 640, 480);
				
				$req = $db->query("SELECT * FROM images ORDER BY id DESC");
				$row = $req->fetch(PDO::FETCH_ASSOC);
				$id = $row['id'] + 1;
				$address = 'photos/' . $id . '.png';
				$req = $db->prepare('INSERT INTO images (user, img) VALUES (?, ?)');
				$req->execute(array($_SESSION['login'], $address));
				imagepng($image_1, $address);
			}
			else
				echo("error3");
		}
		else
			echo("error1");
	}
	else
		echo("error2");
}
?>