<?php

namespace modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class PersoItem extends \BFW_Sql\Classes\Modeles
{
	/**
	 * Nom de la table
	 */
	protected $_name = 'perso_item';
	
	/**
	 * Retourne l'instance Sql_Select de la requête comptant le nombre d'item en vente pour un perso
	 * 
	 * @param string $idPerso : Le champ sur lequel est mit l'id du perso (ex: p.idPerso)
	 * 
	 * @return ressource/null : La ressource Sql_Select, null si erreur sur le paramètre.
	 */
	public function subQ_nbVente($idPerso)
	{
		$default = null;
		if(!is_string($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Paramètre idPerso incorrecT.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'COUNT(idItem)')
					->where('idPerso='.$idPerso)
					->where('vendu=0')
					->where('enVente=1');
		return $req;
	}
	
	/**
	 * Retourne l'instance Sql_Select de la requête comptant le nombre d'item en attente pour un perso
	 * 
	 * @param string $idPerso : Le champ sur lequel est mit l'id du perso (ex: p.idPerso)
	 * 
	 * @return ressource/null : La ressource Sql_Select, null si erreur sur le paramètre.
	 */
	public function subQ_nbWait($idPerso)
	{
		$default = null;
		if(!is_string($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Paramètre idPerso incorrecT.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'COUNT(idItem)')
					->where('idPerso='.$idPerso)
					->where('vendu=0')
					->where('enVente=0');
		return $req;
	}
	
	/**
	 * Retourne la valeur des ventes de la semaine pour un User
	 * 
	 * @param int  $idUser  : L'id de l'user
	 * @param Date $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date $dateFin : L'objet Date de la date de fin de la semaine
	 * 
	 * @return int : Le nombre de po gagné dans la semaine
	 */
	public function getPoVenteSemaineUser($idUser, $dateDeb, $dateFin)
	{
		$default = 0;
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, array('po' => 'SUM(poGagne)'))
					->where('idUser=:user', array(':user' => $idUser))
					->where('dateVendu >= "'.$dateDeb->getSql().'"')
					->where('dateVendu <= "'.$dateFin->getSql().'"');
		$res = $req->fetchRow();
		
		if($res) {return (int) $res['po'];}
		else {return $default;}
	}
	
	/**
	 * Retourne la valeur des ventes de la semaine pour un perso
	 * 
	 * @param int  $idPerso  : L'id du perso
	 * @param Date $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date $dateFin : L'objet Date de la date de fin de la semaine
	 * 
	 * @return int : Le nombre de po gagné dans la semaine
	 */
	public function getPoVenteSemainePerso($idPerso, $dateDeb, $dateFin)
	{
		$default = 0;
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, array('po' => 'SUM(poGagne)'))
					->where('idPerso=:perso', array(':perso' => $idPerso))
					->where('dateVendu >= "'.$dateDeb->getSql().'"')
					->where('dateVendu <= "'.$dateFin->getSql().'"');
		$res = $req->fetchRow();
		
		if($res) {return (int) $res['po'];}
		else {return $default;}
	}
	
	/**
	 * Retourne la valeur des ventes de la semaine pour un User
	 * 
	 * @param int  $idUser  : L'id de l'user
	 * @param Date $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date $dateFin : L'objet Date de la date de fin de la semaine
	 * 
	 * @return array : Le nombre de po gagné dans la semaine pour chaque perso de l'user
	 */
	public function getPoVenteSemaineAllPerso($idUser, $dateDeb, $dateFin)
	{
		$default = array();
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		/*
		SELECT 
		`p`.*, 
		(
		  SELECT SUM(pi.poGagne) 
		  FROM `perso_item` AS `pi` 
		  WHERE 
		    pi.idPerso=p.idPerso AND 
		    dateVendu >= "2013-12-02 00:00:00" AND 
		    dateVendu <= "2013-12-08 23:59:59"
		) AS po
		
		
		FROM `perso` AS `p`
		
		WHERE p.idUser=1 
		 */
		
		$subReq =  $this->select()
						->from(array('pi' => $this->_name), 'SUM(poGagne)')
						->where('pi.idPerso=p.idPerso')
						->where('dateVendu >= "'.$dateDeb->getSql().'"')
						->where('dateVendu <= "'.$dateFin->getSql().'"');
		
		$req = $this->select()
					->from(array('p' => 'perso'), '')
					->subQuery($subReq, 'po')
					->where('p.idUser=:user', array(':user' => $idUser));
		
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
}
?>