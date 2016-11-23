<?php
	include 'class/config.php';
	
	$dateSystem = new DateTime(substr(date('c'), 0, 10));
	$dateDem = new DateTime(substr(date('c'), 0, 10));
	$dateDem->add(date_interval_create_from_date_string('1 days'));
	$dateDemDem = new DateTime(substr(date('c'), 0, 10));
	$dateDemDem->add(date_interval_create_from_date_string('2 days'));

	// on se connecte à MySQL 
	$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
	if (!$connect->set_charset("utf8")) $connect->character_set_name();
	if ($connect->connect_errno) exit();
	
	// on crée la requête SQL 
	$sql = 'SELECT * FROM evenement WHERE dateDebut BETWEEN "'.substr($dateDem->format('Y-m-d H:i:s'),0,19).'" AND "'.substr($dateDemDem->format('Y-m-d H:i:s'),0,19).'"'; 

	// on envoie la requête 
	$res = $connect->query($sql);

	// on fait une boucle qui va faire un tour pour chaque enregistrement 
	while($data = $res->fetch_assoc()){
		$sql2 = 'SELECT nom FROM categorie WHERE id='.$data['categorie']; 
		$res2 = $connect->query($sql2);
		$data2 = $res2->fetch_assoc();
		$sql3 = 'SELECT prenom FROM utilisateur WHERE email="'.$data['utilisateur'].'"'; 
		$res3 = $connect->query($sql3);
		$data3 = $res3->fetch_assoc();
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
			<h1 style=\"text-align:center;color:#E4E4E4;\">".$data3['prenom'].", vous avez un événement demain ! :)</h1>
			<div style=\"text-align:center;padding-bottom:25px;\">
			<p style=\"color:#E4E4E4;\">Demain, l'événement ".$data['nom']." aura lieu ! Pensez à vous préparer pour demain!</p>
			<p style=\"color:#E4E4E4;\">Votre événement commencera à cette date : <span style=\"color:#27ae60;font-weight:bold;\">".substr($data['dateDebut'],0,10)."</span></p>
			<p style=\"color:#E4E4E4;\">Catégorie  : <span style=\"color:#27ae60;font-weight:bold;\">".$data2['nom']."</span></p>
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
			mail($data['utilisateur'],"The Galactic Shrewd Calendar : Ne ratez pas votre événement !",$message,$header);
    } 
	// on ferme la connexion à mysql 
	$connect->close();	
?>


