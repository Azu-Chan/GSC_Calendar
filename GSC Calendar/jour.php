<?php 
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();
   if(!isset($_SESSION["mail"])) header("location:login.php");
	setlocale(LC_ALL, 'fr_FR');
	$_SESSION['jour']=((isset($_SESSION['jour'])) ? $_SESSION['jour'] : 0);
	include_once 'class/Calendar.php';
	$cal=new Calendar();
	$vue=$cal->vueJour($_SESSION['jour']);
	if(isset($_POST['passe'])){ 
		$_SESSION['jour']--;
		$vue=$cal->vueJour($_SESSION['jour']);
		unset($_POST['passe']);
	}
	if(isset($_POST['futur'])){ 
		$_SESSION['jour']++;
		$vue=$cal->vueJour($_SESSION['jour']);
		unset($_POST['futur']);
	}
	if($_SESSION['jour']<0) $jourVoulu=date("j")+$_SESSION['jour']; 
	else $jourVoulu=date("j")+$_SESSION['jour'];
	$anneeVoulu=$jourVoulu/366+date("Y");
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
<title>Calendrier Jour</title>
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
	        <li><a href="#">JOUR</a></li>
	        <li><a href="semaine.php">SEMAINE</a></li>
	        <li><a href="calendrier.php">MOIS</a></li>
	        <li><a href="rechercheEvenement.php">RECHERCHE</a></li>
	      </ul>
		</div>
	</nav>
	<h1><?php echo utf8_encode(ucfirst(strftime("%A %d %B %Y",mktime(0,0,0,date("m"),$jourVoulu,date("Y")))));?></h1>
	<form method="POST" action="jour.php"><button name="passe" class="btnNav" style="float:left;font-size:25px;"><span style="float:left;font-size:25px;"class="glyphicon glyphicon-arrow-left"></span></button></form>
	<form method="POST" action="jour.php"><button name="futur" class="btnNav" style="float:right;font-size:25px;"><span style="float:right;font-size:25px;"class="glyphicon glyphicon-arrow-right"></span></button></form>
	<div style="clear:both;"></div>
	<div id="calendar" class="cal-context" style="width: 100%;">
		<div id="cal-day-box">
			<div class="row-fluid clearfix cal-row-head">
				<div class="span1 col-xs-1 cal-cell">Heure</div>
				<div class="span11 col-xs-11 cal-cell">Evénements</div>
			</div>
			<div id="cal-day-panel" class="clearfix">
				<div id="cal-day-panel-hour">
					<?php echo $vue;?>			
				</div>	
			</div>
		</div>
	</div>
</body>
</html>