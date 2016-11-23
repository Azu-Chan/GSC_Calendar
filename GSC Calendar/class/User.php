<?php 
include 'config.php';
class User {
	private $bd;

	public function __construct(){
		$this->bd = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE);
		if (!$this->bd->set_charset("utf8")) $this->bd->character_set_name();
	}

	public function enregistrer($prenom,$nom,$mail,$mdp){
		$mail=strtolower($mail);
		$verifEnregistrement=$this->bd->query("SELECT * FROM utilisateur WHERE email='$mail'")->num_rows;
		if($verifEnregistrement!=0)
			return false;
		$bdMdp=md5($mdp);
		return $this->bd->query("INSERT INTO utilisateur (prenom,nom,email,mdp) VALUES ('$prenom','$nom','$mail','$bdMdp')") or die(mysqli_connect_errno()."OUCCHHHEE");

	}

	public function estMembre($mail){
		$mail=strtolower($mail);
		$nbLignes=$this->bd->query("SELECT * FROM utilisateur WHERE email='$mail'")->num_rows;
		if($nbLignes==0)return false;
		return true;
	}

	public function connecter($mail,$mdp){
		$mail=strtolower($mail);
		$bdMdp=md5($mdp);
		$nbLignes=$this->bd->query("SELECT * FROM utilisateur WHERE email='$mail' AND mdp='$bdMdp'")->num_rows;
		if($nbLignes==0)return false;
		$ip=$_SERVER['REMOTE_ADDR'];
		$this->bd->query("INSERT INTO connexion (utilisateur,ip) VALUES ('$mail','$ip')");
		return true;	
	}
	
	public function explorateurDownload($IDEvent){
	
	$sql = "SELECT url FROM media WHERE evenement=".$IDEvent;
	$res = $this->bd->query($sql);
	
       // boucler sur tous les resultats de la requete
       while ($data = $res->fetch_assoc()) {
		   $name = substr(mb_strrchr($data['url'],'/',false), 1);
           echo "<a href=".$data['url']." download=\"".$name."\">$name</a><br />\n";
       }
    }


	public function mail($prenom,$nom,$mail,$mdp){
		$mail=strtolower($mail);
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
		<h1 style=\"text-align:center;color:#E4E4E4;\">Bienvenue dans la super-élite !</h1>
		<div style=\"text-align:center;padding-bottom:25px;\">
		<p style=\"color:#E4E4E4;\">Voici les informations que vous nous avez communiquées nous espérons vous revoir bientôt au sein de notre système galactique ! </p>
		<p style=\"color:#E4E4E4;\">Prénom : <span style=\"color:#27ae60;font-weight:bold;\">".$prenom."</span></p>
		<p style=\"color:#E4E4E4;\">Nom : <span style=\"color:#27ae60;font-weight:bold;\">".$nom."</span></p>
		<p style=\"color:#E4E4E4;\">E-mail : <span style=\"color:#27ae60;font-weight:bold;\">".$mail."</span></p>
		<p style=\"color:#E4E4E4;\">Mot de passe : <span style=\"color:#27ae60;font-weight:bold;\">".$mdp."</span></p>
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
		mail($mail,"The Galactic Shrewd Calendar : Codes de connexion intersidéraux",$message,$header);
	}

}
?>