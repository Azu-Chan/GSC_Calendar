<?php
class Event{
public $nom;
public $resume;
public $frequence;
public $dateDeb;
public $dateFin;
public $listeFichier;
public $categorie;
public $utilisateur;

	public function __construct($nom, $resume, $frequence, $dateDeb, $dateFin, $listeFichier, $categorie, $utilisateur){
		$this->nom = $nom;
		$this->resume = $resume;
		$this->frequence = $frequence;
		$this->dateDeb = $dateDeb;
		$this->dateFin = $dateFin;
		$this->listeFichier = $listeFichier;
		$this->categorie = $categorie;
		$this->utilisateur = $utilisateur;
	}
	
	//Produit la requete SQL qui permet l'insertion dans la base de donnée
	public function toSQL(){
		
		$requete = $requete.'INSERT INTO evenement (utilisateur, categorie, dateDebut, dateFin, nom, resume, frequence) ';
		$requete = $requete.'VALUES ("'.$this->utilisateur.'", "'.$this->categorie.'", "'.$this->dateDeb.'", "'.$this->dateFin.'", "'.$this->nom.'", "'.$this->resume.'", "'.$this->frequence.'"); ';
		
		foreach($this->listeFichier as $value) {
			$requete = $requete.'INSERT INTO media (evenement, url) ';
			$requete = $requete.'VALUES ((SELECT id FROM evenement WHERE utilisateur="'.$this->utilisateur.'" ORDER BY id DESC limit 1), "'.$value.'"); ';
		}
		return $requete;
	}
}
?>