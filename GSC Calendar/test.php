<?php 
include 'class/EventDB.php';
$e=new EventDB();
$e->generer(36);
echo $e->joursRestantSemaine(2,2,2016)."<br><br>";
echo "Appartient a la semaine <br>";
echo $e->appartientSemaine(2,2,2016);
echo "<br>Possede a la semaine <br>";
echo $e->nbSemaine();
?>