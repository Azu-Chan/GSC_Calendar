<?php
include 'EventDB.php';
class Calendar{
private $bd;

	public function __construct(){
		$this->bd = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE);
		if (!$this->bd->set_charset("utf8")) $this->bd->character_set_name();
		if ($this->bd->connect_error) 
		  die('Connect Error (' . $this->bd->connect_errno . ') '.$this->bd->connect_error);
	}
	/*public function evenement($jour,$mois,$annee,$utilisateur){}*/
	public function evenement($jour,$mois,$annee,$utilisateur){
		$msg="<div class=\"events-list\">";
		$msg.=$this->evenementPonctuelle($jour,$mois,$annee,$utilisateur);
		$msg.=$this->placerFrequent($jour,$mois,$annee,$utilisateur);
		$msg.=$this->placerLong($jour,$mois,$annee,$utilisateur);
		$msg.="</div>";
		return $msg;
	}

	private function evenementPonctuelle($jour,$mois,$annee,$utilisateur){
		$date=date("Y-m-d",mktime(0,0,0,$mois,$jour,$annee))."%";
		$res=$this->bd->query("SELECT id FROM evenement WHERE dateDebut LIKE '$date%' AND dateFin LIKE dateDebut AND utilisateur='$utilisateur' AND frequence LIKE \"Ponctuelle\"");
		$e=$res->num_rows;
		if($e==0)return "";
		$msg="";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			$couleur=$evenement->getCouleur();
		//	if($evenement->frequence())array_push($this->evenementFrequent,$evenement);
		//	if($evenement->long())array_push($this->$evenementLong,$evenement);
			$msg.="						
				<a href=\"event.php?id=".$id."\" class=\"pull-left event\" style=\"background-color:".$couleur."; \"></a>";
		}
		return $msg;
	}

	private function placerFrequent($jour,$mois,$annee,$utilisateur){
		$msg="";
		$res=$this->bd->query("SELECT id FROM evenement WHERE utilisateur='$utilisateur'");
		$e=$res->num_rows;
		if($e==0)return "";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			if($evenement->estFrequent($jour,$mois,$annee))
				{
					$couleur=$evenement->getCouleur();
					$id=$evenement->getId();
					$msg.="						
						<a href=\"event.php?id=".$id."\" class=\"pull-left event\"style=\"background-color:".$couleur."; \"></a>";
				}
		}
		return $msg;
	}


	private function placerLong($jour,$mois,$annee,$utilisateur){
		$msg="";
		$res=$this->bd->query("SELECT id FROM evenement WHERE utilisateur='$utilisateur'");
		$e=$res->num_rows;
		if($e==0)return "";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			if($evenement->estLong()){
				if($evenement->estEnCours($jour,$mois,$annee))
					{
						$couleur=$evenement->getCouleur();
						$id=$evenement->getId();
						$msg.="						
							<a href=\"event.php?id=".$id."\" class=\"pull-left event\" style=\"border-radius:0px; background-color:".$couleur."; \"></a>";
					}
			}
		}
		return $msg;

	}

	public function evenementSemaine($jour,$mois,$annee,$utilisateur,$position){
		$msg="";
		$msg.=$this->evenementPonctuelleSemaine($jour,$mois,$annee,$utilisateur,$position);
		$msg.=$this->placerFrequentSemaine($jour,$mois,$annee,$utilisateur,$position);
		$msg.=$this->placerLongSemaine($jour,$mois,$annee,$utilisateur,$position);
		return $msg;
	}

	private function evenementPonctuelleSemaine($jour,$mois,$annee,$utilisateur,$position){
		$date=date("Y-m-d",mktime(0,0,0,$mois,$jour,$annee))."%";
		$res=$this->bd->query("SELECT id FROM evenement WHERE dateDebut LIKE '$date%' AND dateFin LIKE dateDebut AND utilisateur='$utilisateur' AND frequence LIKE \"Ponctuelle\"");
		$e=$res->num_rows;
		if($e==0)return "";
		$msg="";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			$couleur=$evenement->getCouleur();
		//	if($evenement->frequence())array_push($this->evenementFrequent,$evenement);
		//	if($evenement->long())array_push($this->$evenementLong,$evenement);
			$nom=$evenement->getNom();
			$msg.="
			<div class=\"cal-row-fluid non-hover\">
				<div class=\"cal-cell1 cal-offset".$position." day-highlight dh-event-info\" style=\"background-color:".$couleur."; \">					
					<a href=\"event.php?id=".$id."\" class=\"cal-event-week\">".$nom."</a>
				</div>
			</div>";
		}
		return $msg;
	}

	private function placerFrequentSemaine($jour,$mois,$annee,$utilisateur,$position){
		$msg="";
		$res=$this->bd->query("SELECT id FROM evenement WHERE utilisateur='$utilisateur'");
		$e=$res->num_rows;
		if($e==0)return "";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			if($evenement->estFrequent($jour,$mois,$annee))
				{
					$couleur=$evenement->getCouleur();
					$id=$evenement->getId();
					$nom=$evenement->getNom();
					$msg.="
					<div class=\"cal-row-fluid non-hover\">
						<div class=\"cal-cell1 cal-offset".$position." day-highlight dh-event-info\" style=\"background-color:".$couleur."; \">				
							<a href=\"event.php?id=".$id."\" class=\"cal-event-week\">".$nom."</a>
						</div>
					</div>";
				}
		}
		return $msg;
	}

		private function placerLongSemaine($jour,$mois,$annee,$utilisateur,$position){
		$msg="";
		$res=$this->bd->query("SELECT id FROM evenement WHERE utilisateur='$utilisateur'");
		$e=$res->num_rows;
		if($e==0)return "";
		while($id=$res->fetch_assoc()['id']){//Plusieurs event par jour
			$evenement=new EventDB();
			$evenement->generer($id);
			if($evenement->estLong()){
				if($evenement->estEnCours($jour,$mois,$annee))
					{
						$duree=$evenement->joursRestantSemaine($jour,$mois,$annee);
						$couleur=$evenement->getCouleur();
						$id=$evenement->getId();
						$nom=$evenement->getNom();
						if($duree>=1){
							$msg.="
							<div class=\"cal-row-fluid non-hover\">
								<div class=\"cal-cell".$duree." cal-offset".$position." day-highlight dh-event-info\" style=\"background-color:".$couleur."; \">				
									<a href=\"event.php?id=".$id."\" class=\"cal-event-week\">".$nom."</a>
								</div>
							</div>";
						}
				}
			}
		}
		return $msg;
	}

	public function vueJour($jour){
		$message="";
		for($i=0;$i<24;$i++){
		$h=date("H",mktime($i,0,0,1,1,2015));
		$message.="
		<div class=\"cal-day-hour\">
		<div class=\"row-fluid cal-day-hour-part\">
			<div class=\"span1 col-xs-1\"><b>".$h.":00</b></div>
			<a href=\"creerEvenement.php\"><div class=\"span11 col-xs-11\" style=\"height:100%;\"></div></a>
		</div>";
		}
		return $message;
	}

	public function vueSemaine($semaine,$mail){
		setlocale(LC_ALL, 'fr_FR');
		$jourEnPlus=$semaine*7;
		$jour=date("z")+1+$jourEnPlus;
		$lundiAvant=$jour-date("N")+1;
		$message="";
		$evenement="";
		for($i=0;$i<7;$i++){
			$jl=$lundiAvant+$i;
			$jour=ucfirst(strftime("%A",mktime(0,0,0,1,$jl,date("Y"))));
			$iMois=utf8_encode(strftime("%d %B",mktime(0,0,0,1,$jl,date("Y"))));
			$evenement.=$this->evenementSemaine($jl,1,date("Y"),$mail,$i);
			$date=date("Y-m-d",mktime(0,0,0,1,$jl,date("Y")));
			$message.="
			<div class=\"cal-cell1 cal-day-today\"><a href=\"creerEvenement.php?date=".$date."\" style=\"width:100%;\">".$jour."<br>
				<small><span>".$iMois."</span></small></a>
			</div>";
		}
		$message.="</div>
			<hr>";
		$message.=$evenement;
		return $message;
	}

	public function vueMois($moisEnMoins,$mail){
		$anneeEnMoins=$moisEnMoins/12;
		$moisEnMoins=$moisEnMoins%12;
		$message="
		<div class=\"cal-row-fluid cal-before-eventlist\">
		";
		$mois=date("m")+$moisEnMoins;
		if($mois<=0)$mois+=12;//+12;
		//$annee=date("Y")+$anneeEnMoins;
		$annee=date("Y",mktime(0,0,0,date("m")+$moisEnMoins,1,date("Y")));
		$message.=$this->comblageMois($mois,$annee)[1];
		$cJour=$this->comblageMois($mois,$annee)[0];
		for($i=0;$i<cal_days_in_month(CAL_GREGORIAN, $mois, $annee);$i++){
			$vi=$i+$cJour;
			if($vi%7==0 && $i!=0)$message.="
		</div>
		<div class=\"cal-row-fluid cal-before-eventlist\">
				";
			if($vi%7==5 || $vi%7==6) $weekend="cal-day-weekend";
			else $weekend="";
			$jour=$i+1;
			$evenement=$this->evenement($jour,$mois,$annee,$mail);
			$moisD=date("m",mktime(0,0,0,$mois,$jour,$annee));
			$jourD=date("d",mktime(0,0,0,$mois,$jour,$annee));
			$message.="
			<div class=\"cal-cell1 cal-cell\" >
			<a href=\"creerEvenement.php?date=".substr($annee,0,4)."-".$moisD."-".$jourD."\">
				<div class=\"cal-month-day cal-day-inmonth ".$weekend."\">
					<span class=\"pull-right\" >".$jour."</span>
					<div class=\"creerEvenement\"></div>".$evenement."
				</div>
			</a>
			</div>
			";
		}
		$j=1;
		if(cal_days_in_month(CAL_GREGORIAN, $mois, $annee)+$cJour>35){
			for($i=cal_days_in_month(CAL_GREGORIAN, $mois, $annee)+$cJour;$i<42;$i++){
				$message.="
			<div class=\"cal-cell1 cal-cell\" >
				<div class=\"cal-month-day cal-day-outmonth\">
					<span class=\"pull-right\" >".$j."</span>
				</div>
			</div>
			";
				$j++;
			}
		}else{
			for($i=cal_days_in_month(CAL_GREGORIAN, $mois, $annee)+$cJour;$i<35;$i++){
				$message.="
			<div class=\"cal-cell1 cal-cell\" >
				<div class=\"cal-month-day cal-day-outmonth\">
					<span class=\"pull-right\" >".$j."</span>
				</div>
			</div>
			";
				$j++;
			}
		}
		$message.="</div>";
		return $message;


	}


	private function comblageMois($mois,$an){
		$retour=array();
		$aCombler=date("N",mktime(0,0,0,$mois,1,$an))-1;//ou w
		if($mois==1)$mois=13;
		$jour=cal_days_in_month(CAL_GREGORIAN,$mois-1,$an);
		$message="";
		for($i=$jour-$aCombler;$i<$jour;$i++){
			$j=$i+1;
			$message.="
			<div class=\"cal-cell1 cal-cell\" >
				<div class=\"cal-month-day cal-day-outmonth cal-month-first-row\">
					<span class=\"pull-right\" >".$j."</span>
				</div>
			</div>
			";
		}
		array_push($retour,$aCombler);
		array_push($retour,$message);
		$message="";
		return $retour;
	}
	/*public function vueAnnee(){}*/ 
}
?>


