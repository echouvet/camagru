<?php
include 'config/setup.php';

$file = $_FILES["fileupload"]["tmp_name"];

if (empty($file))
{
	header('Location: montage.php?error=8');
	exit;
}
if(isset($_POST["upload"]))
{
	if (isset($_SESSION['login']))
	{
		if (isset($_POST['img']) && $_POST['img'] !== 'none')
		{
		    if(getimagesize($file) != FALSE) 
		    {
		    	if ($_FILES["fileupload"]["size"] < 5000000) 
				{
					$imageFileType = strtolower(pathinfo($_FILES["fileupload"]["name"], PATHINFO_EXTENSION));
				    if($imageFileType === "png")
				    {
				    	if (move_uploaded_file($file, "tmp.png") == true)
				    	{
    							$image_1 = imagecreatetruecolor(640, 480);
								$tmp = imagecreatefrompng('tmp.png');
								$width = imagesx($tmp);
								$height = imagesy($tmp);
    							imagecopyresampled($image_1, $tmp, 0, 0, 0, 0, 640, 480, $width, $height);
								$image_2 = imagecreatefrompng('img/' . $_POST['img'] . '.png');
								imagecopy($image_1, $image_2, 0, 0, 0, 0, 640, 480);
								$req = $db->query("SELECT * FROM images ORDER BY id DESC");
								$row = $req->fetch(PDO::FETCH_ASSOC);
								$id = $row['id'] + 1;
								$address = 'photos/' . $id . '.png';
								$req = $db->prepare('INSERT INTO images (user, img) VALUES (?, ?)');
								$req->execute(array($_SESSION['login'], $address));
								imagepng($image_1, $address);
								header('Location: montage.php');
								exit;
					    }
					    else 
					    {
					        header('Location: montage.php?error=4');
					        exit;
					    }
				    }
				    else
				  	{
					    header('Location: montage.php?error=5');
					    exit;
					}
				}
				else
				{
					header('Location: montage.php?error=6');
					exit;
				}
		    }
		    else 
		    {
				header('Location: montage.php?error=7');
				exit;
			}
		}
		else
		{
			header('Location: montage.php?error=1');
			exit;
		}
	}
    else
    {
		header('Location: montage.php?error=2');
		exit;
	}
}

?>