<?php
	include 'class/config.php';
	
	$dateAct = new DateTime(substr(date('c'), 0, 10));
	
	function explorateurDownload($path){
	$dir = $path;
	//  si le dossier pointe existe
	if (is_dir($dir)) {

    // si il contient quelque chose
    if ($dh = opendir($dir)) {

       // boucler tant que quelque chose est trouve
       while (($file = readdir($dh)) !== false) {

           // affiche le nom qui est un lien de download si ce n'est pas un element du systeme
           if( $file != '.' && $file != '..') {
           echo "<a href=\"http://piwit.xyz/".$path."/".$file."\" download=\"".$file."\">$file</a><br />\n";
           }
       }
       // on ferme la connexion
       closedir($dh);
    }
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
<title>Administration</title>
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
<div class="center">
<?php

// Le mot de passe a été envoyé et est bon
if (isset($_POST['mot_de_passe']) && $_POST['mot_de_passe'] == MDP_ADM)
{ ?>
<h2> Gestion de comptes </h2>
	
<?php 
	// on se connecte à MySQL 
	$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
	if (!$connect->set_charset("utf8")) $connect->character_set_name();
	if ($connect->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" . $connect->connect_errno . ") " . $connect->connect_error;
	}
	
	// on crée la requête SQL 
	$sql = 'SELECT * FROM utilisateur'; 

	// on envoie la requête 
	$res = $connect->query($sql);

	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = $res->fetch_assoc()) 
    { 
    // on affiche les informations de l'enregistrement en cours 
    echo '<br><br>Pr&eacute;nom : '.$data['prenom'].' ; <br>Nom : '.$data['nom'].' ; <br>Email : '.$data['email'].' ; <br>MDP (crypt&eacute;) : '.$data['mdp'].'<br>';
	// vérification de l'activité du compte
	$sql2 = 'SELECT dateConnexion FROM connexion WHERE connexion.utilisateur="'.$data['email'].'" ORDER BY dateConnexion DESC'; 
	$res2 = $connect->query($sql2);
	$data2 = $res2->fetch_assoc();
	if($data2['dateConnexion']!=null){
		$dateConnex = new DateTime(substr($data2['dateConnexion'], 0, 10));
		$leTemps = date_diff($dateAct, $dateConnex);
	}
	else $leTemps = date_diff($dateAct, $dateAct);
?>
	<form action="./traitementRelance.php" method="post">
	<input type="hidden"  name="mail"  value="<?php echo $data['email'] ?>">
	<input type="hidden"  name="prenom"  value="<?php echo $data['prenom'] ?>">
	<input type="hidden"  name="nom"  value="<?php echo $data['nom'] ?>">
	<?php if($leTemps->format('%a')>182) {?><input type="<?php if($leTemps->format('%a')>182) echo "submit"; else echo "button"?>" style="color:black" value="<?php if($leTemps->format('%a')>182) echo "Relancer membre"; else echo "-- NULL --"?>" />
	<?php }?></form><br><br>
	<form action="./traitementDelete.php" method="post">
	<input type="hidden"  name="clefPrim"  value="<?php echo $data['email'] ?>">
	<input type="submit" style="color:black" onclick="return confirm('Etes-vous s&ucirc;r de vouloir supprimer ce membre ?')" value="Supprimer membre" />
	</form>	
<?php
	echo "<br>";
    } 
	// on ferme la connexion à mysql 
	$connect->close();	
?>
	<br><br>
	<h2> Contenu du dossier 'uploadFichiers' </h2>
	<?php explorateurDownload('./uploadsFichiers'); ?>
	
<?php }
else // affichage de la demande de mot de passe ou mot de passe incorrect ou non envoyé
{ ?>
	<form action="admin.php" method="post">
        <p>
		Mot de passe requis pour les fonctions d'administration<br>
        <input type="password" style="color:black" name="mot_de_passe">
        <input type="submit" style="color:black" value="Valider">
        </p>
    </form>
<?php }
?>
	<br><br>
</div>
</body>
</html>