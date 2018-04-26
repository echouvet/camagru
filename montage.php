<?php
	include 'config/setup.php';
	if (empty($_SESSION['login']))
	{
		header('Location: index.php');
		exit;
	}
	if (isset($_POST['delete']) && !empty($_POST['delete']))
	{
		$req = $db->prepare("SELECT * FROM images WHERE id=?");
			$req->execute(array($_POST['delete']));
		if ($req->rowcount() > 0)
		{
			$req = $db->prepare("DELETE FROM images WHERE id=?");
			$req->execute(array($_POST['delete']));
			unlink('photos/' . $_POST['delete'] . '.png');
		}
	}
	$error = "none";
	if ($_GET['error'] === '1')
		$error = "Please select one of the three options on the left side of the camera in order to compile your photo";
	if ($_GET['error'] === '2')
		$error = "Please log in to capture a photo";
	if ($_GET['error'] === '3')
		$error = "The camera must be on to take a photo";
	if ($_GET['error'] === '4')
		$error = "Sorry, there was an error with the address whilst uploading your file.";
	if ($_GET['error'] === '5')
		$error = "Error: Only  PNG files are allowed.";
	if ($_GET['error'] === '6')
		$error = "Error: your uploaded file is too large.";
	if ($_GET['error'] === '7')
		$error = "Error: Uploaded file is not an image.";
	if ($_GET['error'] === '7')
		$error = "Error: You must select an image to compile through upload";
	if ($_GET['error'] === '8')
		$error ="Error: Please select a valid file to compile through an upload";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Pic Cells</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">

	</head>
	<body>
	<?php include 'header.php' ?>
	
		<div class=stream><video id="video"></video>
			<form method="post" action=upload.php enctype="multipart/form-data">
			<div class="option">
					<input type="radio" name="img" id="fire" value="fire"> <BR />
					<label for="fire"> <img  class=fire src=img/minifire.png></label><BR /><HR />
					<input type="radio" name="img" id="vador" value="vador"><BR />
					<label for="vador"> <img class=vador src=img/minivador.png></label><BR /><HR />
					<input type="radio" name="img" id="bat" value="bat"><BR />
					<label for="bat"> <img class=bat src=img/minibat.png></label><BR />
			</div>
			<input type=submit id="startbutton" name="button" value="Capture Photo" class=button></input><BR /><BR /><BR /><HR /><BR />
				<input type="file" name="fileupload" id="fileupload">
	  			<input type="submit" value="Upload Image" name="upload" class=button>

			</form>
		</div>
		<div class=miniatures>
		<?php 
			$req = $db->prepare("SELECT * FROM images WHERE user=? ORDER BY creation_date DESC LIMIT 20");
				$req->execute(array($_SESSION['login']));
			while ($row = $req->fetch(PDO::FETCH_ASSOC)) 
			{ ?>
				<form method=post action=montage.php>
					<input type="image" class=delete src="img/delete.png" name="delete" value="<?=$row["id"]?>">
				</form>
				<img class=miniphoto src= <?php echo $row['img']; ?> ></img>
			<?php } ?> 
		</div>
		<?php if($error !== "none") { ?>
		<h1 class=error> <?php echo $error; ?></h1> <?php } ?>
		<canvas id="canvas"></canvas>
		<script type="text/javascript" src="camera.js"></script>
	<?php include 'footer.php'; ?>
	</body>
</html>