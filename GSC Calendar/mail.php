<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="/img/favicon.png" />
<title>Mail</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-static-top" id="nav">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="login.php">
	        <img alt="GSC" src="img/long.png" width="176px" height="100%">
	      </a>
	    </div>
	  </div>
	</nav>
	<h1 class="centre">Bienvenue dans la super-élite !</h1>
	<div class="container" style="text-align:center;padding-bottom:25px;">
	<p>Voici les informations que vous nous avez communiqués nous espérons vous revoir bientôt au sein de notre système galactique ! </p>
	<p>Prénom : <span>Lucas<?php echo $prenom;?></span></p>
	<p>Nom : <span>Marie<?php echo $nom;?></span></p>
	<p>E-mail : <span>lucas.marie.2b@gmail.com<?php echo $mail;?></span></p>
	<p>Mot de passe : <span>starwars3<?php echo $mdp;?></span></p>
	</div>
	<div class="imgCentre"><a href="login.php"><img alt="The Galactic Shrewd Calendar" src="img/symbole.PNG"></a></div>
</body>
</html>