<?php
	
function uploaderFichier($fichierUp){
	$dossier = 'uploadsFichiers/';
     $fichier = basename($fichierUp['name']);
	$taille_maxi = 5000000;
	$taille = filesize($fichierUp['tmp_name']);
	$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.pdf', '.doc', '.docx', '.txt');
	$extension = strrchr($fichierUp['name'], '.'); 
	//Début des vérifications de sécurité...
	if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	{
		 $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt, doc, docx ou pdf';
	}
	if($taille>$taille_maxi)
	{
		 $erreur = 'Le fichier est trop gros...';
	}
	if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
	{
     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     if(move_uploaded_file($fichierUp['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
     {
          echo 'Upload effectu&eacute;&eacute; avec succ&egrave;s !';
     }
     else //Sinon (la fonction renvoie FALSE).
     {
          echo 'Echec de l\'upload !';
     }
	}
	else
	{
		 echo $erreur;
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
<title>Test upload</title>
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
    uploaderFichier($_FILES['fichier1']); echo "<br>";
	uploaderFichier($_FILES['fichier2']); echo "<br>";
	uploaderFichier($_FILES['fichier3']); echo "<br>";
	uploaderFichier($_FILES['fichier4']); echo "<br>";
	uploaderFichier($_FILES['fichier5']); echo "<br>";
?>
	
	<br><br>
	<form action="./admin.php" method="post">
	<input type="submit" style="color:black" value="Retour">
	</form>	
</body>
</html>