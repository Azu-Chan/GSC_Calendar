<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<title>Relance de membre</title>
<link rel="icon" type="image/png" href="/img/favicon.png" />
</head>
<body>
	<nav class="navbar navbar-inverse navbar-static-top" id="nav">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="#">
	        <img alt="GSC" src="img/long.png" width="176px" height="100%">
	      </a>
	    </div>
	  </div>
	</nav>
	
<?php 

	echo $_POST['email'];
		$message="
		<!DOCTYPE html>
		<html lang=\"fr\">
		<head>
		<meta charset=\"utf8\">
		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\">
		<link rel=\"stylesheet\" href=\"http://piwit.xyz/bootstrap/css/bootstrap.min.css\">
		<link rel=\"stylesheet\" href=\"http://piwit.xyz/style.css\">
		<link rel=\"icon\" type=\"image/png\" href=\"http://piwit.xyz/img/favicon.png\" />
		<title>Mail</title>
		</head>
		<body style=\"	width: 100%;
						height: 100%;
						padding: 0px;
						margin: 0px;
						background-color:#000000;
						color:#E4E4E4;\">
	    <a class=\"navbar-brand\" href=\"http://piwit.xyz/login.php\">
	        <img alt=\"GSC\" src=\"http://piwit.xyz/img/long.png\" width=\"176px\" height=\"100%\">
	    </a>
		<h1 style=\"text-align:center;color:#E4E4E4;\">".$_POST['prenom'].", on a besoin de vous ! :)</h1>
		<div style=\"text-align:center;padding-bottom:25px;\">
		<p style=\"color:#E4E4E4;\">Cela fait plus de 6 mois que vous ne vous êtes pas connecté, ne nous oubliez pas !! Vous pouvez vous connecter en cliquant sur le logo ci-dessous ! Voici un récapitulatif de vos coordonnées, utilisez votre mot de passe pour vous connecter dès maintenant !</p>
		<p style=\"color:#E4E4E4;\">Prénom : <span style=\"color:#27ae60;font-weight:bold;\">".$_POST['prenom']."</span></p>
		<p style=\"color:#E4E4E4;\">Nom : <span style=\"color:#27ae60;font-weight:bold;\">".$_POST['nom']."</span></p>
		<p style=\"color:#E4E4E4;\">E-mail : <span style=\"color:#27ae60;font-weight:bold;\">".$_POST['mail']."</span></p>
		</div>
		<div class=\"imgCentre\" style=\"
										background-color: #000000;
										border-top: 1px solid #27ae60;
										height:500px;
										\"
										>
			<a href=\"http://piwit.xyz/login.php\"><img alt=\"The Galactic Shrewd Calendar\" src=\"http://piwit.xyz/img/symbole.PNG\" style=
				\"	display: block;
					width:220px;
					height:220px;
					margin:auto;
					margin-top: 10px;
				\"></a></div>
		</body>
		</html>
		";
		$header='Content-type: text/html; charset=utf8'."\r\n";
		$header .= 'From: The Galactic Shrewd Calendar <conseil.galactique@piwit.xyz>' . "\r\n";
		if(mail($_POST['mail'],"The Galactic Shrewd Calendar : Vous nous manquez !",$message,$header)) echo "mail envoy&eacute";
		else echo "&eacute;chec envoi mail.";
		
?>
	<br><br>
	<button type="button" onclick="self.location.href='admin.php'" style="color:black" name="Retour">Retour</button>
</body>
</html>