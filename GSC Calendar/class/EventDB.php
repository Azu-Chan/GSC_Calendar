<?php
include 'config.php';
class EventDB{
private $bd;
private $id;
private $nom;
private $resume;
private $frequence;
private $dateDeb;
private $dateFin;
private $listeFichier;
private $categorie;
private $utilisateur;

	public function __construct(){
		$this->bd = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD,DB_DATABASE);
		if (!$this->bd->set_charset("utf8")) $this->bd->character_set_name();
		if ($this->bd->connect_error) 
		  die('Connect Error (' . $this->bd->connect_errno . ') '.$this->bd->connect_error);
	}

	public function generer($id){
		$this->listeFichier = array();
		$this->id=$id;
		$this->nom=$this->bd->query("SELECT nom FROM evenement WHERE id='$id'")->fetch_assoc()['nom'];
		$this->resume=$this->bd->query("SELECT resume FROM evenement WHERE id='$id'")->fetch_assoc()['resume'];
		$this->frequence=$this->bd->query("SELECT frequence FROM evenement WHERE id='$id'")->fetch_assoc()['frequence'];
	   	$this->dateDeb=$this->bd->query("SELECT dateDebut FROM evenement WHERE id='$id'")->fetch_assoc()['dateDebut'];
	 	$this->dateFin=$this->bd->query("SELECT dateFin FROM evenement WHERE id='$id'")->fetch_assoc()['dateFin'];
		$this->categorie=$this->bd->query("SELECT c.nom FROM evenement e JOIN categorie c ON e.categorie=c.id  WHERE e.id='$id'")->fetch_assoc()['nom'];
		$this->utilisateur=$this->bd->query("SELECT utilisateur FROM evenement WHERE id='$id'")->fetch_assoc()['utilisateur'];
		$res = $this->bd->query("SELECT * FROM media WHERE media.evenement='".$id."'");
		while($data = $res->fetch_assoc()){
			array_push($this->listeFichier, $data['url']);
		}
	}

	public function appartientA($mail){
		if($this->utilisateur==$mail)return true;
		return false;
	}

	public function getId(){
		return $this->id;
	}
	
	public function getListeFichier(){
		return $this->listeFichier;
	}
	
	public function getResume(){
		return $this->resume;
	}

	public function getNom(){
		return $this->nom;
	}
	
	public function getCategorie(){
		return $this->categorie;
	}
	
	public function getFrequence(){
		return $this->frequence;
	}
	
	public function getDateDeb(){
		return $this->dateDeb;
	}
	
	public function getDateFin(){
		return $this->dateFin;
	}
	
	public function getUtilisateur(){
		return $this->utilisateur;
	}

	public function frequence(){
		if($this->frequence == "Ponctuelle") return false;
		return true;
	}

	public function estFrequent($jour,$mois,$annee){
		switch ($this->frequence){
			case "Hebdomadaire":
				$dateDebut=date("N",strtotime($this->dateDeb));
				$date=date("N",mktime(0,0,0,$mois,$jour,$annee));
				if($dateDebut==$date){return true;}
				return false;
				break;
			case "Mensuelle":
				$dateDebut=date("j",strtotime($this->dateDeb));
				if($jour==$dateDebut)return true;
				if($dateDebut>=28 && $dateDebut<=31)
					if(cal_days_in_month(CAL_GREGORIAN,date("m",mktime(0,0,0,$mois,$jour,$annee)),date("Y",mktime(0,0,0,$mois,$jour,$annee)))==$jour)return true;
				return false;
				break;
			case "Trimestrielle":
				$dateDebutJour=date("j",strtotime($this->dateDeb));
				$date=date("m",mktime(0,0,0,$mois,$jour,$annee));
				$dateDebutMois=date("m",strtotime($this->dateDeb));
				$difference=$date-$dateDebutMois;	
				if($difference%3==0){
					if($jour==$dateDebutJour)return true;
					if($dateDebutJour>=28 && $dateDebutJour<=31)
						if(cal_days_in_month(CAL_GREGORIAN,date("m",mktime(0,0,0,$mois,$jour,$annee)),date("Y",mktime(0,0,0,$mois,$jour,$annee)))==$jour)return true;
				}
				return false;
				break;
			case "Annuelle":
				$dateDebutJour=date("j",strtotime($this->dateDeb));
				$dateDebut=date("j m",strtotime($this->dateDeb));
				$dateDebutMois=date("m",strtotime($this->dateDeb));
				$dateMois=date("m",mktime(0,0,0,$mois,$jour,$annee));
				$date=date("j m",mktime(0,0,0,$mois,$jour,$annee));
				if($date==$dateDebut)return true;
				if($dateDebutJour>=28 && $dateDebutJour<=31)
					if(cal_days_in_month(CAL_GREGORIAN,date("m",mktime(0,0,0,$mois,$jour,$annee)),date("Y",mktime(0,0,0,$mois,$jour,$annee)))==$jour && $dateDebutMois==$dateMois)return true;
				return false;
			break;
			case "Ponctuelle":return false;break;
			default:return false;break;
		}

	}
	
	public function getCouleur(){
		$cat=$this->categorie;
		$utilisateur=$this->utilisateur;
		$c=$this->bd->query("SELECT couleur FROM categorie WHERE nom='$cat' AND (utilisateur IS NULL or utilisateur='$utilisateur')")->fetch_assoc()['couleur'];
		return $c;
	}

	public function estLong(){
		if(date("Y m d",strtotime($this->dateDeb))==date("Y m d",strtotime($this->dateFin)))return false;
		return true;
	}

	public function estEnCours($jour,$mois,$annee){//date diff peut être ???
		$date=new DateTime(date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee)));
		$debut=new DateTime(date('Y-m-d', strtotime($this->dateDeb)));
		$fin=new DateTime(date('Y-m-d', strtotime($this->dateFin)));
		if (($date->getTimestamp() >= $debut->getTimestamp()) && ($date->getTimestamp() <= $fin->getTimestamp()))
		  return true;
		else
		  return false; 

	}

	public function duree(){
		$seconde = strtotime($this->dateFin) - strtotime($this->dateDeb);
		$jour=floor($seconde/3600/24);
		return $jour;
	}

	public function estCommenceDepuis($jour,$dateJ,$dateM,$dateY){
		$date=date("Y-m-d",mktime(0,0,0,$dateM,$dateJ,$dateY));
		$seconde = strtotime($date) - strtotime($this->dateDeb);
		$jourDepuis=floor($seconde/3600/24);
		if($jourDepuis==$jour)return true;
		return false;
	}

	public function nbSemaine(){
		$nb=(int)($this->duree()/7);
		return $nb;
	}

	public function appartientSemaine($jour,$mois,$annee){
		/*$res=mktime(0,0,0,$mois,$jour,$annee)-strtotime($this->dateDeb);
		$j=date("j",$res);
		return (int)($j/7)+1;
		$div=$this->joursRestant($jour,$mois,$annee);
		$s=$this->nbSemaine()-(int)($div/7)+1;
		return $s;*/
		$anneeARaj=date("Y",mktime(0,0,0,$mois,$jour,$annee))-date("Y",strtotime($this->dateDeb));
		$semaineARaj=$anneeARaj*52;
		$semaineDeb=date("W",strtotime($this->dateDeb));
		$semaineAct=date("W",mktime(0,0,0,$mois,$jour,$annee))+$semaineARaj;
		$semaine=$semaineAct-$semaineDeb;
		return $semaine;
	}

	public function joursRestantSemaine($jour,$mois,$annee){
		/*$jourARajouter=$semaine*7;
		$date=new DateTime(date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee)));
		$semaine=new DateTime(date('Y-m-d', strtotime($this->dateDeb)));
		$semaine=$semaine->add(new DateInterval('P'.$jourARajouter.'D'));
		$restant=$semaine->getTimestamp()-$date->getTimestamp();
		return floor($restant/3600/24);	*/
		/*$jourD;
		for($i=0;$i<7;$i++){
			$jourD=$jour+$i;			
			if(date("N",mktime(0,0,0,$mois,$jourD,$annee))==7)break;
		}
		$jourD++;
					if($semaine==$this->nbSemaine())
			{
				echo "nous sommes dans le if <br><br>";
				$if;
				for($i=0;$i<7;$i++){
					$jourSemaine=$jour+$i;
					if(date("N",mktime(0,0,0,$mois,$jourSemaine,$annee))==date("N",strtotime($this->dateFin))){$if=$i;break;}
				}
				$if++;
				return $if;
			}*/
		if((date("N",mktime(0,0,0,$mois,$jour,$annee))==1) || (date("Y m j",mktime(0,0,0,$mois,$jour,$annee))==date("Y m j",strtotime($this->dateDeb)))){
		$semaine=$this->appartientSemaine($jour,$mois,$annee);
			$retour;
			for($i=0;$i<7;$i++){
				$jourSemaine=$jour+$i;
				if((date("N",mktime(0,0,0,$mois,$jourSemaine,$annee))==7) || (date("Y m j",mktime(0,0,0,$mois,$jourSemaine,$annee))==date("Y m j",strtotime($this->dateFin)))){$retour=$i;break;}
			}
			$retour++;
			return $retour;
		}
		return 0;
	}

	public function joursRestant($jour,$mois,$annee){
		$date=new DateTime(date('Y-m-d',mktime(0,0,0,$mois,$jour,$annee)));
		$fin=new DateTime(date('Y-m-d', strtotime($this->dateFin)));
		$restant=$fin->getTimestamp()-$date->getTimestamp();
		return floor($restant/3600/24);
	}
}
?>