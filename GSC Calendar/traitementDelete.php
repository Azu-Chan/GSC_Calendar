<?php
	include 'class/config.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<title>Suspression tuple</title>
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
	// on se connecte à MySQL 
	$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
	if (!$connect->set_charset("utf8")) $connect->character_set_name();
	if ($connect->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" . $connect->connect_errno . ") " . $connect->connect_error;
	}
	
	// on crée la requête SQL 
	$subSql = 'SELECT id FROM evenement WHERE utilisateur="'.$_POST['clefPrim'].'" ORDER BY id ASC';
	$sql2 = 'DELETE FROM evenement WHERE utilisateur="'.$_POST['clefPrim'].'"'; 
	$sql3 = 'DELETE FROM categorie WHERE utilisateur="'.$_POST['clefPrim'].'"'; 
	$sql4 = 'DELETE FROM connexion WHERE utilisateur="'.$_POST['clefPrim'].'"'; 
	$sql5 = 'DELETE FROM utilisateur WHERE email="'.$_POST['clefPrim'].'"'; 

	// on envoie la requête 
	$res = $connect->query($subSql);
	while($data = $res->fetch_assoc()){
			$sqld = 'SELECT url FROM media WHERE evenement='.$data['id'].'; ';
			$resd = $connect->query($sqld);
			while($datad = $resd->fetch_assoc()) {
				unlink("uploadsFichiers/".substr($datad['url'],16));
			}
		$sql = 'DELETE FROM media WHERE evenement='.$data['id'];
		if ($connect->query($sql) === TRUE) {} 
		  else {
		  echo "Erreur de suspression medias, ID_ERROR: " . $connect->error."<br><br>";
		  }
	}
	if ($connect->query($sql2) === TRUE) {} 
	  else {
		echo "Erreur de suspression evenements, ID_ERROR: " . $connect->error."<br><br>";
	  }
	if ($connect->query($sql3) === TRUE) {} 
	  else {
		echo "Erreur de suspression categories, ID_ERROR: " . $connect->error."<br><br>";
	  }
	if ($connect->query($sql4) === TRUE) {} 
	  else {
		echo "Erreur de suspression historique, ID_ERROR: " . $connect->error."<br><br>";
	  }
	if ($connect->query($sql5) === TRUE) {
		echo "Membre supprim&eacute; avec succ&egrave;s";
	} else {
		echo "Erreur de suspression compte, ID_ERROR: " . $connect->error;
	  }

	$connect->close();	

?>
	<br><br>
	<button type="button" onclick="self.location.href='admin.php'" style="color:black" name="Retour">Retour</button>
</body>
</html>