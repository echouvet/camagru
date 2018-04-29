<?php include 'config/setup.php'; 
	
	$montagelink = "login.php?error=1";	
	if (isset($_SESSION['login']) && !empty($_SESSION['login']))
		$montagelink = "montage.php";
	$start = '0';
	if (isset($_POST['page']))
		$start = (intval($_POST['page']) * '6') - '6';
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="camagru.css">
	</head>
	<body>
		 <?php include 'header.php'; ?>
		 <a href= <?php echo $montagelink ?> class=montagelink> <h2>COMPILATION OF A PIC CELL </h2></a>
		<div class=gallery>
			<form method="post" action="index.php">
				<div class=pagination>
					<input type=submit name='page' value='1'>
					<input type=submit name='page' value='2'>
					<input type=submit name='page' value='3'>
					<input type=submit name='page' value='4'>
					<input type=submit name='page' value='5'>
					<input type=submit name='page' value='6'>
					<input type=submit name='page' value='7'>
					<input type=submit name='page' value='8'>
					<input type=submit name='page' value='9'>
					<input type=submit name='page' value='10'>
				</div></form>
			<?php $req = $db->prepare("SELECT * FROM images ORDER BY creation_date DESC LIMIT 6 OFFSET :start");
			$req->bindValue(':start', intval($start), PDO::PARAM_INT);
			$req->execute();
			while ($row = $req->fetch(PDO::FETCH_ASSOC)) 
			{ ?>
				<div class=galleryset>
					<p> Author: <?php echo $row['user']; ?> </p>
					<img class=galleryphoto src= <?php echo $row['img']; ?> ></img>
					<HR />
					<div class=comments>
						<?php 
						$reqcom = $db->prepare("SELECT * FROM comments where :id = img_id"); 
						$reqcom->bindValue(':id', $row['id'], PDO::PARAM_INT);
						$reqcom->execute();
						if ($reqcom->rowCount() == '0')
							echo 'NO COMMENTS';
						else
						{
							while ($comrow = $reqcom->fetch(PDO::FETCH_ASSOC)) 
								echo $comrow['user'] . ':  ' . $comrow['comment'] . "<BR /><HR />";
						}
						?>
					</div>
					<HR />
					<form method="post" action="comments.php">
						<input type="text" name="comment" value="" class=button>
						<input type="submit" name="<?=$row["id"]?>" value="Comment" class=button>
					</form>
				</div>
			<?php } ?> 
		</div>
		<?php include 'footer.php'; ?>
	</body></html>