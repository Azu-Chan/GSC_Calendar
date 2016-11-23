<?php 
session_start();
if(isset($_SESSION["mail"])) header("location:calendrier.php");
include_once 'class/User.php';
$user=new User();
$message="Transmettez vos codes d'autorisation";
if(isset($_POST['valider'])){
	if(empty($_POST['email']))
	 $errM="Il faut entrer une adresse e-mail ;)";
	if(empty($_POST['pass']))
	 $errMdp="Il faut entrer un mot de passe ;)";
	if(!$user->estMembre($_POST['email'])){
		$message="Désolé mais tu n'es pas un membre de l'élite galactique :(";
	}else{
		if(!$user->connecter($_POST['email'],$_POST['pass']))
			$message="Ton mot de passe n'est pas répertorié dans nos données galactique !";
		else {$message="Félicitations tu es connecté !!";$_SESSION['mail']=strtolower($_POST['email']); header( "refresh:1;url=calendrier.php" );}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="/img/favicon.png" />
<title>Connexion</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-static-top" id="nav">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="#">
	        <img alt="GSC" src="img/long.png" width="176px" height="100%">
	      </a>
	    </div>
	    <div class="nav navbar-nav navbar-right"><a class="btn btn-default" id="inscription" href="inscription.php">Rejoindre la super-élite galactique</a></div>
	  </div>
	</nav>
	<h1 class="centre"><?php echo $message;?></h1>
	<div class="center">
		<form class="form-horizontal" role="form" action="login.php" method="POST">
			<div class="form-group">
				<label class="control-label col-sm-2 login">E-mail :</label><div class="col-sm-10"><input class="form-control" type="text" name="email" required="true" maxlength="255" placeholder="Ton adresse e-mail "><?php echo "<label class=\"erreur\">".$errM."</label>";?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2 login">Mot de passe :</label><div class="col-sm-10"><input class="form-control" type="password" name="pass" required="true" maxlength="32" placeholder="Ton mot de passe"><?php echo "<label class=\"erreur\">".$errMdp."</label>";?></div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default" name="valider">Valider</button> 
				</div>
			</div>
		</form>
	</div>
        
</body>
</html>