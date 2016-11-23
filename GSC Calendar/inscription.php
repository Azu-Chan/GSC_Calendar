<?php 
	session_start();
	include_once 'class/User.php';
	// Génère un mdp avec 4 chiffres et 4 lettres (MAJ ou min) de manière aléatoire placés aléatoirement
	function generMdp(){
		
		$countChar = 0;
		$countInt = 0;
		$alea;
		
		$mdp = "";
		
		while(strlen($mdp)<8){
			$alea = mt_rand(1,2);
			if($alea == 1 && $countInt<4){
				$mdp = $mdp . chr(mt_rand(48,57));
				$countInt++;
			}
			if($alea == 2 && $countChar<4){
				$alea = mt_rand(1,2);
				if($alea == 1) $mdp = $mdp . chr(mt_rand(65,90));
				if($alea == 2) $mdp = $mdp . chr(mt_rand(97,122));
				$countChar++;
			}
		}	
		return $mdp;
	}
	function verifMdp($mdp){
	
		if(!is_string($mdp)) return false;
		if(strlen($mdp)!=8) return false;
		
		$countChar = 0;
		$countInt = 0;
		$i = 0;
		$temp;
		
		while($i<=7){
			$temp = substr($mdp,$i,1);
			if(ord($temp)>=48 && ord($temp)<=57) $countInt++;
			if(ord($temp)>=65 && ord($temp)<=90) $countChar++;
			if(ord($temp)>=97 && ord($temp)<=122) $countChar++;
			$i++;
		}
		
		if($countChar!=4) return false;
		if($countInt!=4) return false;
		
		return true;
	}
	$errP = null; $errN = null; $errM = null; $errMdp = null;
	$message="Rejoins la force galactique !!";
	$inscription=true;
	if(isset($_POST['valider'])){
		$regexNom = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
		if(!empty($_POST['prenom']) && preg_match($regexNom,$_POST['prenom'])==0)
			$prenom=$_POST['prenom'];
		else {$errP="Entre un prénom correct :)";$inscription=false;}
		if(!empty($_POST['nom']) && preg_match($regexNom,$_POST['nom'])==0)
			$nom=$_POST['nom'];
		else {$errN="Entre un nom correct :)";$inscription=false;}
		if(!empty($_POST['email']) && (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false))
			$email=$_POST['email'];
		else {$errM="Entre une adresse mail correct :)";$inscription=false;}
		if(isset($_POST['passAuto'])){
			$mdp=generMdp();
		}
		else{
			if(!empty($_POST['pass']) && !empty($_POST['passVerif'] && $_POST['pass']==$_POST['passVerif']) && verifMdp($_POST['pass']))
				$mdp=$_POST['pass'];
			else{$errMdp="Vérifie tes données ou coche la case ;)";$inscription=false;}
		}
		if($inscription){
			$user=new User();
			if(!($user->estMembre($_POST['email']))){
			$enregistrement=$user->enregistrer($_POST['prenom'],$_POST['nom'],$_POST['email'],$mdp);
			if(!$enregistrement){
				$message="Oups il y a eu un problème ! Désolé ...";
			}else {if($user->connecter($_POST['email'],$mdp)){
				$message="Félicitations tu es un membre de la super élite galactique dorénavant !";
				$_SESSION['mail']=strtolower($_POST['email']); 
				$user->mail($_POST['prenom'],$_POST['nom'],$_POST['email'],$mdp);
				header( "refresh:1;url=calendrier.php" );
			}}
		}else $message="Vos codes d'identifications sont déjà utilisés";
		}else $message="Tes données ne sont pas correctes corrige-les et réessaye !";
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
<title>Inscription</title>
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
	<h1 class="centre"><?php echo $message;?></h1>
	<div class="center">
	<form class="form-horizontal" role="form" action="inscription.php" method="post">
		<div class="form-group">
			<label class="control-label col-sm-2"><span>*</span>Prénom :</label><div class="col-sm-10"><input class="form-control" type="text" name="prenom" required="true" maxlength="109" placeholder="Ton prénom"><?php echo "<label class=\"erreur\">".$errP."</label>";?></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span>*</span>Nom : </label><div class="col-sm-10"><input class="form-control" type="text" name="nom" required="true" maxlength="109" placeholder="Ton nom"><?php echo "<label class=\"erreur\">".$errN."</label>";?></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span>*</span>E-mail : </label><div class="col-sm-10"><input class="form-control" type="text" name="email"  required="true" maxlength="255" placeholder="Ton adresse e-mail"><?php echo "<label class=\"erreur\">".$errM."</label>";?></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">Mot de passe : </label><div class="col-sm-5"><input class="form-control" type="password" name="pass" maxlength="32" placeholder="4 lettres et 4 chiffres !"></div><div class="col-sm-5"><input class="form-control" type="password" name="passVerif" placeholder="Retape ton mot de passe"><?php echo "<label class=\"erreur\">".$errMdp."</label>";?></div> 
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label><input type="checkbox" name="passAuto"> Génére automatiquement un mot de passe </label>
				</div>
			</div>
		</div>
		<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		<button type="submit" class="btn btn-default" name="valider">Valider</button> </div></div>
	</form>
	</div>
</body>
</html>
