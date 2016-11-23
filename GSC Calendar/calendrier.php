<?php 
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();
   if(!isset($_SESSION["mail"])) header("location:login.php");
	setlocale(LC_ALL, 'fr_FR');
	$_SESSION['mois']=((isset($_SESSION['mois'])) ? $_SESSION['mois'] : 0);
	include_once 'class/Calendar.php';
	$cal=new Calendar();
	$vue=$cal->vueMois($_SESSION['mois'],$_SESSION['mail']);
	if(isset($_POST['passe'])){ 
		$_SESSION['mois']--;
		$vue=$cal->vueMois($_SESSION['mois'],$_SESSION['mail']);
		unset($_POST['passe']);
	}
	if(isset($_POST['futur'])){ 
		$_SESSION['mois']++;
		$vue=$cal->vueMois($_SESSION['mois'],$_SESSION['mail']);
		unset($_POST['futur']);
	}
	$diff=date("m")+$_SESSION['mois'];
	if($diff<=0) 
		$moisVoulu=date("m")+$_SESSION['mois']+12;	
	else 
		$moisVoulu=date("m")+$_SESSION['mois'];
	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="bootstrap/css/calendar.css">
<link rel="stylesheet" href="calendrier.css">
<link rel="icon" type="image/png" href="/img/favicon.png" />
<title>Calendrier Mois</title>
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
	        <li><a href="#">MOIS</a></li>
	        <li><a href="rechercheEvenement.php">RECHERCHE</a></li>
	      </ul>
		</div>
	</nav>

	<h1><?php echo utf8_encode(ucfirst(strftime("%B %Y",mktime(0,0,0,date("m")+$_SESSION['mois'],1,date("Y")))))?></h1>
	<form method="POST" action="calendrier.php"><button name="passe" class="btnNav" style="float:left;font-size:25px;"><span style="float:left;font-size:25px;"class="glyphicon glyphicon-arrow-left"></span></button></form>
	<form method="POST" action="calendrier.php"><button name="futur" class="btnNav" style="float:right;font-size:25px;"><span style="float:right;font-size:25px;"class="glyphicon glyphicon-arrow-right"></span></button></form>
	<div style="clear:both;"></div>
	<div id="calendar" class="cal-context" style="width: 100%;">
		<div class="cal-row-fluid cal-row-head">	
				<div class="cal-cell1">Lundi</div>	
				<div class="cal-cell1">Mardi</div>
				<div class="cal-cell1">Mercredi</div>
				<div class="cal-cell1">Jeudi</div>
				<div class="cal-cell1">Vendredi</div>
				<div class="cal-cell1">Samedi</div>
				<div class="cal-cell1">Dimanche</div>
		</div>
		<div class="cal-month-box">
			<?php echo $vue;?>
		</div>
	</div>
</body>
</html>
