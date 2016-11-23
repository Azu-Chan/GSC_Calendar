<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if(!isset($_SESSION["mail"])) header("location:login.php");
setlocale(LC_ALL, 'fr_FR');
$_SESSION['semaine']=((isset($_SESSION['semaine'])) ? $_SESSION['semaine'] : 0);
include_once 'class/Calendar.php';
$cal=new Calendar();
$vue=$cal->vueSemaine($_SESSION['semaine'],$_SESSION['mail']);
if(isset($_POST['passe'])){ 
	$_SESSION['semaine']--;
	$vue=$cal->vueSemaine($_SESSION['semaine'],$_SESSION['mail']);
	unset($_POST['passe']);
}
if(isset($_POST['futur'])){ 
	$_SESSION['semaine']++;
	$vue=$cal->vueSemaine($_SESSION['semaine'],$_SESSION['mail']);
	unset($_POST['futur']);
}
$jourEnPlus=$_SESSION['semaine']*7;
$jour=date("z")+1+$jourEnPlus;
$semaineVoulu=$jour-date("N")+1;

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
<title>Calendrier Semaine</title>
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
	        <li><a href="#">SEMAINE</a></li>
	        <li><a href="calendrier.php">MOIS</a></li>
	        <li><a href="rechercheEvenement.php">RECHERCHE</a></li>
	      </ul>
		</div>
	</nav>

	<h1><?php echo "Semaine ".date(" W, Y",mktime(0,0,0,1,$semaineVoulu,date("Y")));?></h1>
	<form method="POST" action="semaine.php"><button name="passe" class="btnNav" style="float:left;font-size:25px;"><span style="float:left;font-size:25px;"class="glyphicon glyphicon-arrow-left"></span></button></form>
	<form method="POST" action="semaine.php"><button name="futur" class="btnNav" style="float:right;font-size:25px;"><span style="float:right;font-size:25px;"class="glyphicon glyphicon-arrow-right"></span></button></form>
	<div style="clear:both;"></div>
	<div id="semaine">
	<div id="calendar" class="cal-context" style="width: 100%;">
		<div class="cal-week-box">
			<div class="cal-offset1 cal-column"></div>
			<div class="cal-offset2 cal-column"></div>
			<div class="cal-offset3 cal-column"></div>
			<div class="cal-offset4 cal-column"></div>
			<div class="cal-offset5 cal-column"></div>
			<div class="cal-offset6 cal-column"></div>
			<div class="cal-row-fluid cal-row-head">
					<?php echo $vue;?>
		</div>
	</div>
	</div>
</body>
</html>