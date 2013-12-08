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
		
		$subReq =  $this->select()
						->from(array('pi' => $this->_name), 'SUM(poGagne)')
						->where('pi.idPerso=p.idPerso')
						->where('dateVendu >= "'.$dateDeb->getSql().'"')
						->where('dateVendu <= "'.$dateFin->getSql().'"');
		
		$MPerso = new \modeles\Perso;
		return $MPerso->getReqPoVenteSemaineAllPerso($idUser, $subReq);
	}
	
	/**
	 * Retourne les ventes de la semaine pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * @param Date  $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date  $dateFin : L'objet Date de la date de fin de la semaine
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes dans la semaine pour l'user (qu'importe le perso)
	 */
	public function getVenduSemaineAllPerso($idUser, $dateDeb, $dateFin, $limit, $order)
	{
		$default = array();
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idUser) || !is_array($limit) || !is_array($order))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$correspondance = array(
			'Item' => 'i.text', 
			'Perso' => 'p.nom', 
			'Date' => 'pi.dateVendu', 
			'Achat' => 'pi.typeVente', 
			'Po' => 'pi.poGagne'
		);
		
		$req = $this->select()
					->from(array('pi' => $this->_name), array('typeVente', 'dateVendu', 'poGagne'))
					->join(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=1')
					->where('dateVendu >= "'.$dateDeb->getSql().'"')
					->where('dateVendu <= "'.$dateFin->getSql().'"')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de vente dans la semaine pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * @param Date  $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date  $dateFin : L'objet Date de la date de fin de la semaine
	 * 
	 * @return int : Le nombre de ventes dans la semaine pour l'user (qu'importe le perso)
	 */
	public function getNbVenduSemaineAllPerso($idUser, $dateDeb, $dateFin)
	{
		$default = array();
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=1')
					->where('dateVendu >= "'.$dateDeb->getSql().'"')
					->where('dateVendu <= "'.$dateFin->getSql().'"');
		
		$req->fetchRow();
		return $req->nb_result();
	}
	
	/**
	 * Retourne les ventes pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes pour l'user (qu'importe le perso)
	 */
	public function getVenduAllPerso($idUser, $limit, $order)
	{
		$default = array();
		
		if(!is_int($idUser) || !is_array($limit) || !is_array($order))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$correspondance = array(
			'Item' => 'i.text', 
			'Perso' => 'p.nom', 
			'Date' => 'pi.dateVendu', 
			'Achat' => 'pi.typeVente', 
			'Po' => 'pi.poGagne'
		);
		
		$req = $this->select()
					->from(array('pi' => $this->_name), array('typeVente', 'dateVendu', 'poGagne'))
					->join(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=1')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de vente pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * 
	 * @return int : Le nombre de ventes pour l'user (qu'importe le perso)
	 */
	public function getNbVenduAllPerso($idUser)
	{
		$default = array();
		
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=1');
		
		$req->fetchRow();
		return $req->nb_result();
	}
	
	/**
	 * Retourne les ventes de tout le monde
	 * 
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes
	 */
	public function getVenduAll($limit, $order)
	{
		$default = array();
		
		if(!is_array($limit) || !is_array($order))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$correspondance = array(
			'Item' => 'i.text', 
			'Perso' => 'p.nom', 
			'Date' => 'pi.dateVendu', 
			'Achat' => 'pi.typeVente', 
			'Po' => 'pi.poGagne'
		);
		
		$req = $this->select()
					->from(array('pi' => $this->_name), array('typeVente', 'dateVendu', 'poGagne'))
					->join(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->joinLeft(array('u' => 'users'), 'u.id=pi.idUser', array('nomUser' => 'login'))
					->where('vendu=1')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de ventes de tout le monde
	 * 
	 * @return int : Le nombre de ventes dans la semaine pour l'user (qu'importe le perso)
	 */
	public function getNbVenduAll()
	{
		$req = $this->select()
					->from($this->_name, 'idItem')
					->where('vendu=1');
		
		$req->fetchRow();
		return $req->nb_result();
	}
	
	/**
	 * Retourne les items en attente pour un user
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les item en attente pour l'user (qu'importe le perso)
	 */
	public function getEnAttente($idUser, $limit, $order)
	{
		$default = array();
		
		if(!is_int($idUser) || !is_array($limit) || !is_array($order))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$correspondance = array(
			'Item' => 'i.text', 
			'Perso' => 'p.nom', 
			'Date' => 'pi.dateDebut', 
			'Enchere' => 'pi.enchere', 
			'Rachat' => 'pi.rachat'
		);
		
		$req = $this->select()
					->from(array('pi' => $this->_name), array('dateDebut', 'duree', 'enchere', 'rachat'))
					->join(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=0')
					->where('enVente=0')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre d'item en attente pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * 
	 * @return int : Le nombre d'item en attente pour l'user (qu'importe le perso)
	 */
	public function getNbEnAttente($idUser)
	{
		$default = array();
		
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('vendu=0')
					->where('enVente=0');
		
		$req->fetchRow();
		return $req->nb_result();
	}
}
?>