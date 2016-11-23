<?php 
include 'class/EventDB.php';
	
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["mail"])) header("location:login.php");

$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
		if (!$connect->set_charset("utf8")) $connect->character_set_name();
		if ($connect->connect_errno) {
			echo "Nos serveurs galactiques sont actuellement sabotés par de misérables rebelles :(";// . $connect->connect_errno . ") " . $connect->connect_error;
		}

$message="Spécifiez votre événement galactique !!";
$errN = null;$errC = null;$errDD = null;$errDF = null;$errF = null;
if(isset($_POST['modif'])){
	 if(empty($_POST['nom']))
	 $errN="Entre un nom !";
	else if(empty($_POST['categorie']))
	 $errC="Nous avons besoin d'une catégorie !";
	else if(empty($_POST['dateDeb']))
	 $errDD="Il nous faut une date de commencement super-élite !";
	else if($_POST['dateFin'] < $_POST['dateDeb'])
	 $errDF="";
	else if(empty($_POST['dateFin']))
	 $errDF="Date non valide";
	else if($_POST['frequence'] != "Ponctuelle" && $_POST['dateFin'] != $_POST['dateDeb'])
	 $errF="Un événement long ne peut être fréquent !";
	else{	
		$tab = array();
		if(!empty($_FILES['file1'])) if(uploadFichier($_FILES['file1'])){$fichier1 = "uploadsFichiers/".$_FILES['file1']['name']; $fichier1 = strtr($fichier1, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $fichier1 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier1); array_push($tab, $fichier1);}
		if(!empty($_FILES['file2'])) if(uploadFichier($_FILES['file2'])){$fichier2 = "uploadsFichiers/".$_FILES['file2']['name']; $fichier2 = strtr($fichier2, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $fichier2 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier2); array_push($tab, $fichier2);}
		if(!empty($_FILES['file3'])) if(uploadFichier($_FILES['file3'])){$fichier3 = "uploadsFichiers/".$_FILES['file3']['name']; $fichier3 = strtr($fichier3, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $fichier3 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier3); array_push($tab, $fichier3);}
		if(!empty($_FILES['file4'])) if(uploadFichier($_FILES['file4'])){$fichier4 = "uploadsFichiers/".$_FILES['file4']['name']; $fichier4 = strtr($fichier4, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $fichier4 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier4); array_push($tab, $fichier4);}
		if(!empty($_FILES['file5'])) if(uploadFichier($_FILES['file5'])){$fichier5 = "uploadsFichiers/".$_FILES['file5']['name']; $fichier5 = strtr($fichier5, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); $fichier5 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier5); array_push($tab, $fichier5);}
		if($_POST['categorie']==$l2){
			$sql="UPDATE evenement SET dateDebut='".$_POST['dateDeb']."' ,dateFin='".$_POST['dateFin']."' ,nom='".$_POST['nom']."' ,resume='".$_POST['resume']."' ,frequence='".$_POST['frequence']."' WHERE id=".$_GET['id']."";
		}
		else{
			$sql="UPDATE evenement SET categorie=".$_POST['categorie']." ,dateDebut='".$_POST['dateDeb']."' ,dateFin='".$_POST['dateFin']."' ,nom='".$_POST['nom']."' ,resume='".$_POST['resume']."' ,frequence='".$_POST['frequence']."' WHERE id=".$_GET['id']."";
		}
		if($connect->query($sql) === TRUE) {$message="Evénement modifié !";}
		else $message="L'événement ne peut être crée";// . $connect->error."<br><br>";
	}
	}
$eve = new EventDB();
$eve->generer($_GET["id"]);

if($eve->getUtilisateur() == null) $message = "Cet &eacute;v&eacute;nement n'existe pas !";
else if($eve->getUtilisateur() != $_SESSION["mail"]) $message = "Ce n'est pas votre &eacute;v&eacute;nement ! Cet incident sera reporé au Conseil !";
else{
	$message = $eve->getNom();
	$l2 = $eve->getCategorie();
	$l3 = $eve->getFrequence();
	$l4 = substr($eve->getDateDeb(),0,10);
	$l5 = substr($eve->getDateFin(),0,10);
	$l6=$eve->getResume();
	$l7 = "<p class=\"centre\">Fichier(s) li&eacute;(s) :<br><br>";
	foreach($eve->getlisteFichier() as $value){
		$l7 = $l7."<a style=\"color:white\" href=\"http://piwit.xyz/uploadsFichiers/".substr($value,16)."\" download=\"".substr($value,16)."\">".substr($value,16)."</a><br />";		
	}
	$l7 = $l7."</p>";
	
	if(isset($_POST['suppr'])){
		foreach($eve->getlisteFichier() as $value){
			unlink("uploadsFichiers/".substr($value,16));
		}
		$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
		if (!$connect->set_charset("utf8")) $connect->character_set_name();
		if ($connect->connect_errno) {
			echo "Des rebelles attaquent nos serveurs !";// . $connect->connect_errno . ") " . $connect->connect_error;
		}
		$sql = 'DELETE from media WHERE evenement="'.$eve->getId().'"'; 
		if(!($connect->query($sql) === TRUE)) echo "erreur suspression";
		$sql = 'DELETE from evenement WHERE id="'.$eve->getId().'"'; 
		if(!($connect->query($sql) === TRUE)) echo "erreur suspression";
		unset($eve);
		$message = "Ev&eacute;nement supprim&eacute; !";
		header( "refresh:1;url=event.php" );
	}
}
$sql="SELECT id FROM categorie WHERE utilisateur='".$_SESSION['mail']."' OR utilisateur IS NULL AND nom='".$l2."'";
$valeurCategorie=$connect->query($sql)->fetch_assoc()['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/png" href="/img/favicon.png" />
<!-- YOLO -->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<title><?php echo $message;?></title>
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
	<br>
	<h1 class="centre"><b><?php echo $message;?></b></h1>
<?php
	if($message == $eve->getNom()){
		if ($l7 != "<p class=\"centre\">Fichier(s) li&eacute;(s) :<br><br></p>") echo $l7;
	?>	

		<div class="center">
		<form class="form-horizontal" role="form" id="Event" enctype="multipart/form-data" action="event.php?id=<?php echo $_GET["id"]; ?>" method="POST">
			<div class="form-group">
				<label class="control-label col-sm-2 login">Nom</label><div class="col-sm-10"><input class="form-control" type="text" name="nom" required="true" maxlength="80" placeholder="Code d'identification de l'événement" value="<?php echo $message; ?>"><?php if($errN != null) echo "<label class=\"erreur\">".$errN."</label>";?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2 login">Catégorie</label><div class="col-sm-10"><select class="form-control" type="text" name="categorie" required="true" size="1">
					<option value=<?php echo $valeurCategorie;?>><?php echo $l2; ?></option>
					<?php
						$sqlCatDef = "SELECT id, nom FROM categorie WHERE utilisateur IS NULL";
						$sqlCatPer = "SELECT id, nom FROM categorie WHERE utilisateur='".$_SESSION["mail"]."'";
						$resDef = $connect->query($sqlCatDef);
						$resPer = $connect->query($sqlCatPer);
						$cond = 0;
						while($dataDef = $resDef->fetch_assoc()){
							echo '<option value="'.$dataDef['id'].'">'.$dataDef['nom'];
						}
						while($dataPer = $resPer->fetch_assoc()){
							if($cond == 0){ echo '<option value="">-- Catégorie(s) personnalisée(s) --'; $cond = 1; }
							echo '<option value="'.$dataPer['id'].'">'.$dataPer['nom'];
						}
					?>
				</select>
				<?php if($errC != null) echo "<label class=\"erreur\">".$errC."</label>";?>
				</div>
			</div>
						<div class="form-group">
				<label class="control-label col-sm-2 login">Début</label><div class="col-sm-10"><div class="input-group input-append date " id="datePicker">
							<input type="text" class="form-control" required="true" name="dateDeb" value=<?php echo $l4;?>/>
							<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					<script>
					$(document).ready(function() {
					$('#datePicker')
					    .datepicker({
					        todayBtn: "linked",
							language: "fr",
					        format: 'yyyy-mm-dd'
					    })


					    .on('changeDate', function(e) {
					        // Revalidate the date field
					        $('#eventForm').formValidation('revalidateField', 'date');
					    });

					
					});
					</script><?php if($errDD != null) echo "<label class=\"erreur\">".$errDD."</label>";?>
					</div>
				</div>
			<div class="form-group">
				<label class="control-label col-sm-2 login">Fin</label><div class="col-sm-10"><div class="input-group input-append date " id="datePicker2">
							<input type="text" class="form-control" required="true" name="dateFin" value=<?php echo $l5;?>/>
							<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
						</div>
					<script>
					$(document).ready(function() {
					$('#datePicker2')
					    .datepicker({
					        todayBtn: "linked",
							language: "fr",
					        format: 'yyyy-mm-dd'
					    })


					    .on('changeDate', function(e) {
					        // Revalidate the date field
					        $('#eventForm').formValidation('revalidateField', 'date');
					    });

					
					});
					</script><?php if($errDD != null) echo "<label class=\"erreur\">".$errDD."</label>";?>
					</div>
				</div>
			
			<div class="form-group">
				<label class="control-label col-sm-2 login">Fréquence</label><div class="col-sm-10"><select class="form-control" type="text" name="frequence" size="1">
					<option><?php echo $l3?></option>
					<option>Ponctuelle</option>
					<option>Hebdomadaire</option>
					<option>Mensuelle</option>
					<option>Trimestrielle</option>
					<option>Annuelle</option>
				</select>
				<?php if($errF != null) echo "<label class=\"erreur\">".$errF."</label>";?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2 login">Résumé</label><div class="col-sm-10"><textarea class="form-control" form="Event" name="resume" maxlength="500" placeholder="Résumé de l'événement..." ><?php echo $l6;?></textarea></div>
			</div>
			<br>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default btn-sm evenement" name="modif">
				          Modifier
				     </button>
				</div>
			</div>
			<div class="form-group ">
				<div class="col-sm-offset-2 col-sm-10">
				     <button type="submit" class="btn btn-default btn-sm pull-right evenement" name="suppr">
				          Supprimer <span class="glyphicon glyphicon-remove"></span>
				       </button>
				</div>
			</div>
		</form>
		</div>
		<?php }
	?>
</body>
</html>