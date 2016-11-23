<?php 
include_once 'class/Event.php';
include_once 'class/EventDB.php';
include 'class/config.php';
	
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["mail"])) header("location:login.php");

$connect = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);	
		if (!$connect->set_charset("utf8")) $connect->character_set_name();
		if ($connect->connect_errno) {
			echo "Des rebelles attaquent nos serveurs !";// . $connect->connect_errno . ") " . $connect->connect_error;
		}

$affichageCat = 0;
$printTitre = 1;
		
$message="Recherche intersid&eacute;rale d'&eacute;v&eacute;nement";
$nomEv = null; $dateDebEv = null; $dateFinEv = null; $catEv = null;
if(isset($_POST['valider'])){
	if(empty($_POST['mail']))
	 $message="Nos renseignements nous indiquent que vous n'êtes pas autorisé ici";
	if(!empty($_POST['nom']))
	 $nomEv = $_POST['nom'];
	if(!empty($_POST['dateDeb']))
	 $dateDebEv = $_POST['dateDeb'];
 	if(!empty($_POST['dateFin']))
	 $dateFinEv = $_POST['dateFin'];
  	if(!empty($_POST['categorie']) || $_POST['categorie'] != "")
	 $catEv = $_POST['categorie'];
	if(!empty($_POST['dateFin']) && !empty($_POST['dateDeb']) && ($dateDebEv > $dateFinEv)){
	 $message="Vos coordonnées temporelles sont incorrectes ! ";
	 $nomEv = null; $dateDebEv = null; $dateFinEv = null; $catEv = null;
	 }
	else{	
		$sql = "SELECT * FROM evenement WHERE utilisateur='".$_POST['mail']."'";
		if($nomEv != null) $sql = $sql." AND LOWER (nom) LIKE LOWER ('%".$nomEv."%')";
		if($catEv != null) $sql = $sql." AND categorie=".$catEv;
		$sql = $sql." ORDER BY nom ASC;";
		if($dateDebEv == null && $dateFinEv == null){ // CAS1
			if($connect->query($sql) == TRUE) {$affichageCat=1; $res = $connect->query($sql);}
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";
		}
		if($dateDebEv != null && $dateFinEv == null){ // CAS2
			if($connect->query($sql) == TRUE) $resSupp = $connect->query($sql);
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";
			
			$affichageCat = 2;
			$testAND = 0;
			$sqlTmp = "SELECT * FROM evenement WHERE";
			while($dataSupp = $resSupp->fetch_assoc()){
				$lEvent = new EventDB();
				$lEvent->generer($dataSupp['id']);
				$dateTst = new DateTime($dateDebEv);
				$dateTstDeb = new DateTime($lEvent->getDateDeb());
				$dateTstFin = new DateTime($lEvent->getDateFin());
				if(!($lEvent->frequence()) && $dateTstDeb->format('Y-m-d') == $dateTst->format('Y-m-d')){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				if($lEvent->estLong() && $dateTstDeb->format('Y-m-d') < $dateTst->format('Y-m-d') && $dateTstFin->format('Y-m-d') >= $dateTst->format('Y-m-d')){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				if($lEvent->frequence()){
					if($lEvent->estFrequent(substr($dateDebEv,8,2),substr($dateDebEv,5,2),substr($dateDebEv,0,4))){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				}
			}
			$sqlTmp = $sqlTmp.= " ORDER BY nom ASC;";
			if($testAND == 1){ if($connect->query($sqlTmp) == TRUE) {$affichageCat=1; $res = $connect->query($sqlTmp);}
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";}
		}
		if($dateDebEv == null && $dateFinEv != null){ // CAS3
			if($connect->query($sql) == TRUE) $resSupp = $connect->query($sql);
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";
			
			$affichageCat = 2;
			$testAND = 0;
			$sqlTmp = "SELECT * FROM evenement WHERE";
			while($dataSupp = $resSupp->fetch_assoc()){
				$lEvent = new EventDB();
				$lEvent->generer($dataSupp['id']);
				$dateTst = new DateTime($dateFinEv);
				$dateTstFin = new DateTime($lEvent->getDateFin());
				if(!($lEvent->frequence()) && $dateTstFin->format('Y-m-d') == $dateTst->format('Y-m-d')){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				if($lEvent->frequence()){
					if($lEvent->estFrequent(substr($dateFinEv,8,2),substr($dateFinEv,5,2),substr($dateFinEv,0,4))){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				}
			}
			$sqlTmp = $sqlTmp.= " ORDER BY nom ASC;";
			if($testAND == 1){ if($connect->query($sqlTmp) == TRUE) {$affichageCat=1; $res = $connect->query($sqlTmp);}
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";}
		}
		if($dateDebEv != null && $dateFinEv != null){ // CAS4
			if($connect->query($sql) == TRUE) $resSupp = $connect->query($sql);
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";
			
			$affichageCat = 2;
			$testAND = 0;
			$sqlTmp = "SELECT * FROM evenement WHERE";
			while($dataSupp = $resSupp->fetch_assoc()){
				$lEvent = new EventDB();
				$lEvent->generer($dataSupp['id']);
				$dateTstD = new DateTime($dateDebEv);
				$dateTstF = new DateTime($dateFinEv);
				$dateTstDeb = new DateTime($lEvent->getDateDeb());
				$dateTstFin = new DateTime($lEvent->getDateFin());
				if(!($lEvent->frequence()) && $dateTstDeb->format('Y-m-d') >= $dateTstD->format('Y-m-d') && $dateTstFin->format('Y-m-d') <= $dateTstF->format('Y-m-d')){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
				if($lEvent->frequence()){
					$interval = $dateTstD->diff($dateTstF);
					$incr;
					for($incr=0;$incr<=$interval->format('%a');$incr++){
						$dateDeTest = new DateTime($dateDebEv);
						$strg = $incr.' days';
						$dateDeTest->add(date_interval_create_from_date_string($strg));
						if($lEvent->estFrequent(substr($dateDeTest->format('Y-m-d'),8,2),substr($dateDeTest->format('Y-m-d'),5,2),$dateDeTest->format('Y-m-d'),0,4)){ if($testAND == 0){ $sqlTmp = $sqlTmp.= " id=".$dataSupp['id']; $testAND = 1;} else $sqlTmp = $sqlTmp.= " OR id=".$dataSupp['id'];}
					}
				}
			}
			$sqlTmp = $sqlTmp.= " ORDER BY nom ASC;";
			if($testAND == 1){ if($connect->query($sqlTmp) == TRUE) {$affichageCat=1; $res = $connect->query($sqlTmp);}
			else $message="tsssss.... Il y a du brouillage sur la ligne de connexion. Répétez vos coordonnés.";}
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
<link rel="icon" type="image/png" href="/img/favicon.png" />
<!-- YOLO -->
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script src="datepicker/locales/bootstrap-datepicker.fr.min.js"></script>
<title>Recherche d'&eacute;v&eacute;nement</title>
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
	
	<?php if ($affichageCat==0) { ?>
	<h1 class="centre"><?php echo $message;?></h1>
	<div class="center">
		<form class="form-horizontal" role="form" id="Event" enctype="multipart/form-data" action="rechercheEvenement.php" method="POST">
			<div class="form-group">
				<label class="control-label col-sm-2 login">Nom</label><div class="col-sm-10"><input class="form-control" type="text" name="nom" maxlength="80" placeholder="Nom de l'&eacute;v&eacute;nement"></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2 login">D&eacute;but</label><div class="col-sm-10"><div class="input-group input-append date " id="datePicker">
							<input type="text" class="form-control"  name="dateDeb"/>
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
							<input type="text" class="form-control"  name="dateFin"/>
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
				<label class="control-label col-sm-2 login">Cat&eacute;gorie</label><div class="col-sm-10"><select class="form-control" type="text" name="categorie" size="1">
					<option value="">-- Cat&eacute;gories par d&eacute;faut --
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
							if($cond == 0){ echo '<option value="">-- Cat&eacute;gorie(s) personnalis&eacute;e(s) --'; $cond = 1; }
							echo '<option value="'.$dataPer['id'].'">'.$dataPer['nom'];
						}
					?>
				</select>
				</div>
			</div>
			
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default" name="valider">Rechercher !</button> 
			</div>
			
			<input type="hidden" name="mail" value="<?php echo $_SESSION["mail"]; ?>">
			
		</form><br><br><p class="centre">Un formulaire non rempli affichera tous vos &eacute;v&eacute;nements.
	</div>
	<?php }
	else if($affichageCat == 1){ $printTitre = 0;
		while($data = $res->fetch_assoc()){
			if($printTitre == 0){ echo '<h1 class="centre">Un ou plusieurs &eacute;v&eacute;nements ont &eacute;t&eacute; trouv&eacute;s :</h1>'; $printTitre = 1; }
			$sqlNomCategorie = "SELECT nom FROM categorie WHERE id=".$data['categorie'];
			$res2 = $connect->query($sqlNomCategorie);
			$data2 = $res2->fetch_assoc();
			echo '<p class="centre"><a style="color:white" href="event.php?id='.$data['id'].'"><b>'.$data['nom'].'</b> : commence le <u>'.substr($data['dateDebut'],0,10).'</u>, finis le <u>'.substr($data['dateFin'],0,10).'</u>, appartient &agrave; la cat&eacute;gorie <u>'.$data2['nom'].'</u></a></p>';
		}
	}if($printTitre == 0 || $affichageCat == 2) echo '<h1 class="centre">Si ce n\'est pas ici, &ccedil;a n\'existe pas !</h1>';
	?>
</body>
</html>
