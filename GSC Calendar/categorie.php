<?php 
include 'class/config.php';
	
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["mail"])) header("location:login.php");

$message="Créer votre catégorie !";
$errN = null;$errC = null;
$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
if (!$connect->set_charset("utf8")) $connect->character_set_name();
if ($connect->connect_errno) {
	echo "Des rebelles attaquent nos serveurs !";// . $connect->connect_errno . ") " . $connect->connect_error;
}
if(isset($_POST['valider'])){
	if(empty($_POST['mail']))
	 $message="Nos renseignements nous indiquent que vous n'êtes pas autorisé ici";
	else if(empty($_POST['nom']))
	 $errN="La catégorie a besoin d'un nom !";
	else if(empty($_POST['couleur']) || $_POST['couleur'] == "#27ae60")
	 $errC="Cette couleur n'est pas autorisé !";
	else{	
		$sql = "INSERT INTO categorie (utilisateur, nom, couleur) VALUES ('".$_POST['mail']."', '".$_POST['nom']."', '".$_POST['couleur']."')";
		if($connect->query($sql) === TRUE) {$message="Catégorie crée ! Félicitations agent !";/* header( "refresh:1;url=calendrier.php" );*/}
		else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";// . $connect->error."<br><br>";
	}
	}
$message2="Supprimer une catégorie !";
if(isset($_POST['valider2'])){
	if(empty($_POST['mail2']))
	 $message2="Nos renseignements nous indiquent que vous n'êtes pas autorisé ici";
	else if(empty($_POST['categorie2']))
	 $errN2="Il faut sélectionner une catégorie !";
	else{
		$sqlSube = 'SELECT id from evenement WHERE categorie="'.$_POST['categorie2'].'"';
		$resSube = $connect->query($sqlSube);
		while($dataSube = $resSube->fetch_assoc()) {
			$sqld = 'SELECT url FROM media WHERE evenement='.$dataSube['id'].'; ';
			$resd = $connect->query($sqld);
			while($datad = $resd->fetch_assoc()) {
				unlink("uploadsFichiers/".substr($datad['url'],16));
			}
			$sqld = 'DELETE FROM media WHERE evenement='.$dataSube['id'].'; ';
			$connect->query($sqld);
		}
		
		$sql2 = 'DELETE FROM evenement WHERE categorie="'.$_POST['categorie2'].'"; ';
		$sql2 = $sql2.'DELETE FROM categorie WHERE id="'.$_POST['categorie2'].'"; ';
		if($connect->multi_query($sql2) === TRUE) {$message2="Catégorie supprimée ! Elle ne nous embêtera plus maintenant !"; header( "refresh:1;url=categorie.php" );}
		else $message2="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";// . $connect->error."<br><br>";
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
<title>Catégorie</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-static-top" id="nav">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <a class="navbar-brand" href="login.php">
	        <img alt="GSC" src="img/long.png" width="176px" height="100%">
	      </a>
	    </div>
	  
		   <ul class="nav navbar-nav navbar-right">
	        <li><a href="categorie.php" title="Catégorie"><span class="glyphicon glyphicon-plus"></span></a></li>
	        <li><a href="semaine.php">SEMAINE</a></li>
	        <li><a href="calendrier.php">MOIS</a></li>
	        <li><a href="rechercheEvenement.php">RECHERCHE</a></li>
	      </ul>
		</div>
	</nav>
	
	<h1 class="centre"><?php echo $message;?></h1>
	<div class="center">
		<form class="form-horizontal" role="form" id="Event" enctype="multipart/form-data" action="categorie.php" method="POST">
			<div class="form-group">
				<label class="control-label col-sm-2 login"><span>*</span>Nom</label><div class="col-sm-10"><input class="form-control" type="text" name="nom" required="true" maxlength="20" placeholder="Nom de la catégorie "><?php if($errN != null) echo "<label class=\"erreur\">".$errN."</label>";?></div>
			</div>
			<script src="jscolor/jscolor.js"></script>
			<div class="form-group">
				<label class="control-label col-sm-2 login"><span>*</span>Couleur</label><div class="col-sm-10"><input class="form-control jscolor {hash:true}" value="e4e4e4" name="couleur" required="true"><?php if($errN != null) echo "<label class=\"erreur\">".$errC."</label>";?></div>
			</div>
			
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default" name="valider">Créer la catégorie</button> 
			</div>
			
			
			<input type="hidden" name="mail" value="<?php echo $_SESSION["mail"]; ?>">
			
		</form><br><br>
	</div>
	<h1 class="centre"><?php echo $message2;?></h1>
	<div class="center">
		<form class="form-horizontal" role="form" id="Event" enctype="multipart/form-data" action="categorie.php" method="POST">
			<div class="form-group">
				<label class="control-label col-sm-2 login"><span>*</span>Catégorie</label><div class="col-sm-10"><select class="form-control" type="text" name="categorie2" required="true" size="1">
					<?php
						$sqlCatPer = "SELECT id, nom FROM categorie WHERE utilisateur='".$_SESSION['mail']."'";
						$resPer = $connect->query($sqlCatPer);
						$cond = 0;
						while($dataPer = $resPer->fetch_assoc()){
							if($cond == 0){ echo '<option value="">-- Catégorie(s) personnalisée(s) --'; $cond = 1; }
							echo '<option value="'.$dataPer['id'].'">'.$dataPer['nom'];
						}
						if($cond == 0) echo '<option value="">-- Vous n\'avez aucune catégorie ! --'
					?>
				</select>
				<?php if($errC != null) echo "<label class=\"erreur\">".$errN."</label>";?>
				</div>
			</div>
			<?php if($cond != 0) echo '
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default" name="valider2" onclick="return confirm(\'Etes-vous s&ucirc;r de vouloir supprimer cette catégorie ? Tout événement que vous avez crée avec cette catégorie seront aussi supprimés !\')">Supprimer ma catégorie</button> 
			</div>
			' ?>
			
			<input type="hidden" name="mail2" value="<?php echo $_SESSION["mail"]; ?>">
			
		</form>
	</div>
	<br><br>
</body>
</html>