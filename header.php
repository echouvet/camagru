<div class=header>
	<img class=header src="img/header.png"></img>
	<a href=index.php><img id=title src="img/title.png" alt="title" title="Title"></img></a>
	<div class=log_buttons>
		<?php 
		if (!$_SESSION['login'] || $_SESSION['login'] == ""){ ?> 
			<a href=login.php><div class=login alt=login title=Login>LOGIN</div></a>
			<a href=register.php><div class=login alt=register title=Register>REGISTER</div></a>
		<?php } 
		else if ($_SESSION['login'] && $_SESSION['login'] != "") { ?>
			<a href=manage_account.php><div class=login><?php echo $_SESSION['login'] ?></div></a>
			<a href=logout.php><div class=login>LOG OUT</div></a>
		<?php } ?>
	</div>
</div>