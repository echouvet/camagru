<?php include 'config/setup.php'; 
	
	if (empty($_SESSION['login']))
		header('Location: login.php?error=DONTFORGETABOUTTHIS');

	if (isset($_POST['comment']) && !empty($_POST['comment']))
	{
		$req = $db->prepare('INSERT INTO comments (img_id, user, comment) VALUES (:img_id, :user, :comments)');
		$req->bindValue(':img_id',  intval(array_keys($_POST)[1]), PDO::PARAM_INT);
		$req->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
		$req->bindValue(':comments', $_POST['comment'], PDO::PARAM_STR);
		$req->execute();
		
		$req = $db->prepare('SELECT user FROM images WHERE id = :img_id');
		$req->bindValue(':img_id',  intval(array_keys($_POST)[1]), PDO::PARAM_INT);
		$req->execute();
		$result = $req->fetch(PDO::FETCH_ASSOC);
		
		$req = $db->prepare('SELECT * FROM users WHERE login = ?');
		$req->execute(array($result['user']));
		$result = $req->fetch(PDO::FETCH_ASSOC);
		if ($result['notifications'] === '1') 
		{
		$header="MIME-Version: 1.0\r\n" . 'From:"Pic Cells"'."\n" . 'Content-Type:text/html; charset="uft-8"'."\n" . 'Content-Transfer-Encoding: 8bit';
		$msg = ' <html><body><div align=center>
				YOU HAVE RECEIVED A NEW COMMENT ON YOUR PIC CELLS IMAGE! <BR />'
				. $_SESSION['login'] . ' said : <b>' . $_POST['comment'] . '</b> about your photo. <BR />
				You can see the comments in the gallery here: <a href="http://localhost:8080/index.php> PIC CELLS GALLERY </a>
			</div></body></html>';
		mail($result['email'], "You have a new comment", $msg, $header);
		} 
	}
	header('Location: index.php');
	exit;
?>