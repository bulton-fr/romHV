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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'typeVente', 
						'dateVendu', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'typeVente', 
						'dateVendu', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'typeVente', 
						'dateVendu', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
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
	 * @return int : Le nombre de ventes dans la semaine pour tout le monde
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'dateDebut', 
						'duree', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
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

	/**
	 * Retourne les ventes en cours pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes en cours pour l'user (qu'importe le perso)
	 */
	public function getVenteAllPerso($idUser, $limit, $order)
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'dateDebut', 
						'duree', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idUser=:user', array(':user' => $idUser))
					->where('enVente=1')
					->where('vendu=0')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de vente en cours pour un User
	 * 
	 * @param int   $idUser  : L'id de l'user
	 * 
	 * @return int : Le nombre de ventes en cours pour l'user (qu'importe le perso)
	 */
	public function getNbVenteAllPerso($idUser)
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
					->where('enVente=1')
					->where('vendu=0');
		
		$req->fetchRow();
		return $req->nb_result();
	}
	
	/**
	 * Retourne les ventes en cours de tout le monde
	 * 
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes en cours
	 */
	public function getVenteAll($limit, $order)
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
			'Date' => 'pi.dateDebut', 
			'Enchere' => 'pi.enchere', 
			'Rachat' => 'pi.rachat'
		);
		
		$req = $this->select()
					->from(array('pi' => $this->_name), array(
						'ref', 
						'dateDebut', 
						'duree', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->joinLeft(array('u' => 'users'), 'u.id=pi.idUser', array('nomUser' => 'login'))
					->where('enVente=1')
					->where('vendu=0')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de ventes en cours de tout le monde
	 * 
	 * @return int : Le nombre de ventes en cours pour tout le monde
	 */
	public function getNbVenteAll()
	{
		$req = $this->select()
					->from($this->_name, 'idItem')
					->where('enVente=1')
					->where('vendu=0');
		
		$req->fetchRow();
		return $req->nb_result();
	}

	/**
	 * Retourne les ventes en cours pour un perso
	 * 
	 * @param int   $idPerso : L'id du perso
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les ventes en cours pour le perso
	 */
	public function getVentePerso($idPerso, $limit, $order)
	{
		$default = array();
		
		if(!is_int($idPerso) || !is_array($limit) || !is_array($order))
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'dateDebut', 
						'duree', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('enVente=1')
					->where('vendu=0')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de vente en cours pour un perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * 
	 * @return int : Le nombre de ventes en cours pour le perso
	 */
	public function getNbVentePerso($idPerso)
	{
		$default = array();
		
		if(!is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('enVente=1')
					->where('vendu=0');
		
		$req->fetchRow();
		return $req->nb_result();
	}

	/**
	 * Retourne les items en attente pour un perso
	 * 
	 * @param int   $idPerso : L'id du perso
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les item en attente pour le perso
	 */
	public function getAttentePerso($idPerso, $limit, $order)
	{
		$default = array();
		
		if(!is_int($idPerso) || !is_array($limit) || !is_array($order))
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'dateDebut', 
						'duree', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('enVente=0')
					->where('vendu=0')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre d'item en attente pour un perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * 
	 * @return int : Le nombre d'item en attente pour le perso
	 */
	public function getNbAttentePerso($idPerso)
	{
		$default = array();
		
		if(!is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('enVente=0')
					->where('vendu=0');
		
		$req->fetchRow();
		return $req->nb_result();
	}

	/**
	 * Retourne les item vendu pour un perso
	 * 
	 * @param int   $idPerso : L'id du perso
	 * @param array $limit   : Indique le nombre d'item à retourner.
	 * 							array('start' => Le nombre auquel on commence, 'nb' => Le nombre à retourner)
	 * @param array $order   : Indique comment doit être fait le tri. [0]: sur quoi; [1] l'ordre
	 * 
	 * @return array : Les item vendu pour le perso
	 */
	public function getVenduPerso($idPerso, $limit, $order)
	{
		$default = array();
		
		if(!is_int($idPerso) || !is_array($limit) || !is_array($order))
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
					->from(array('pi' => $this->_name), array(
						'ref', 
						'typeVente', 
						'dateVendu', 
						'enchere', 
						'rachat', 
						'enchere_unite', 
						'rachat_unite', 
						'nb_piece', 
						'notes'
					))
					->joinLeft(array('i' => 'item'), 'i.id=pi.idItem', array('nomItem' => 'text', 'color' => 'color'))
					->joinLeft(array('s' => 'stat'), 's.idStat=pi.idItem', array('nomStat' => 'nom'))
					->joinLeft(array('p' => 'perso'), 'p.idPerso=pi.idPerso', array('nomPerso' => 'nom'))
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('vendu=1')
					->order($correspondance[$order[0]].' '.$order[1])
					->limit(array($limit['start'], $limit['nb']));
		
		$res = $req->fetchAll();
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre d'item vendu pour un perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * 
	 * @return int : Le nombre d'item vendu pour un perso
	 */
	public function getNbVenduPerso($idPerso)
	{
		$default = array();
		
		if(!is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pi' => $this->_name), 'idItem')
					->where('pi.idPerso=:perso', array(':perso' => $idPerso))
					->where('vendu=1');
		
		$req->fetchRow();
		return $req->nb_result();
	}
	
	/**
	 * Ajoute un item dans la liste des items des persos
	 * 
	 * @param int    $idUser   : L'id de l'user
	 * @param int    $idPerso  : L'id du perso
	 * @param string $idItem   : L'id de l'item (avec le premier caractère indiquand le type)
	 * @param array  $idStat   : L'id des stats sur l'item
	 * @param int    $enchere  : Le prix en enchère
	 * @param int    $rachat   : Le prix en rachat
	 * @param int    $Uenchere : Le prix unité en enchère
	 * @param int    $Urachat  : Le prix unité en rachat
	 * @param int    $Unb      : Le nombre d'unité
	 * @param string $date     : La date de la mise en vente
	 * @param int    $duree    : La durée de la mise en vente.
	 * @param string $notes    : Les notes sur l'item
	 * 
	 * @return bool
	 */
	public function add($idUser, $idPerso, $idItem, $idStat, $enchere, $rachat, $Uenchere, $Urachat, $Unb, $date, $duree, $notes)
	{
		$default = false;
		
		$dataVerif = verifTypeData(array(
			array('type' => 'int', 'data' => $idUser),
			array('type' => 'int', 'data' => $idPerso),
			array('type' => 'string', 'data' => $idItem),
			array('type' => 'int', 'data' => $enchere),
			array('type' => 'int', 'data' => $rachat),
			array('type' => 'int', 'data' => $Uenchere),
			array('type' => 'int', 'data' => $Urachat),
			array('type' => 'int', 'data' => $Unb),
			array('type' => 'string', 'data' => $date),
			array('type' => 'int', 'data' => $duree),
			array('type' => 'string', 'data' => $notes)
		));
		
		if(!$dataVerif)
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$nbStat = 0;
		$statOk = array();
		foreach($idStat as $stat)
		{
			if(!is_null($stat) && !is_string($stat))
			{
				if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
				else {return $default;}
			}
			elseif(!is_null($stat) && is_string($stat) && !empty($stat))
			{
				$nbStat++;
				$statOk[] = $stat;
			}
		}
		
		//Traitement idItem
		$typeItem = $idItem[0];
		$idItem = substr($idItem, 1);
		
		$req = $this->select()
					->from($this->_name, array('ref'))
					->where('idUser=:user', array(':user' => $idUser))
					->where('idPerso=:perso', array(':perso' => $idPerso))
					->where('idItem=:item', array(':item' => $idItem));
		$res = $req->fetchAll();
		
		$nb = 1;
		if($res)
		{
			foreach($res as $result)
			{
				$num = (int) substr($result['ref'], (strpos($result['ref'], 'N')+1));
				if($num >= $nb) {$nb = $num+1;}
			}
		}
		
		$ref = 'U'.$idUser.'P'.$idPerso.$typeItem.$idItem.'N'.$nb;
		
		$data = array(
			'ref' => $ref,
			'idPerso' => $idPerso,
			'idUser' => $idUser,
			'idItem' => $idItem,
			'typeItem' => $typeItem,
			'enchere' => $enchere,
			'rachat' => $rachat,
			'enchere_unite' => $Uenchere,
			'rachat_unite' => $Urachat,
			'nb_piece' => $Unb,
			'enVente' => 1,
			'dateDebut' => $date,
			'duree' => $duree,
			'notes' => $notes,
			'vendu' => 0
		);
		
		if($date == '') {$data['enVente'] = 0;}
		
		$req = $this->insert($this->_name, $data);
		
		if($req->execute())
		{
			if($nbStat > 0)
			{
				$MPersoItemStat = new \modeles\PersoItemStat;
				return $MPersoItemStat->add($ref, $statOk);
			}
			else {return true;}
		}
		else {return $default;}
	}

	/**
	 * Fin de vente
	 */
	public function FinDeVente()
	{
		$dateReq = new \BFW\CKernel\Date;
		$dateReq->modify('+3 jours');
		$dateReqSql = $dateReq->getSql();
		
		$req = $this->select()
					->from($this->_name, array('ref', 'dateDebut', 'duree'))
					->where('dateDebut<="'.$dateReqSql.'"')
					->where('enVente=1')
					->where('vendu=0');
		$res = $req->fetchAll();
		
		if($res)
		{
			$dateNow = new \BFW\CKernel\Date;
			
			foreach($res as $result)
			{
				$dateItem = new \BFW\CKernel\Date($result['dateDebut']);
				$dateItem->modify('+'.$result['duree'].' jours');
				
				$dateDiff = $dateNow->DateTime->diff($dateItem->DateTime);
				if($dateDiff->invert == 1)
				{
					$this->update($this->_name, array('enVente' => 0))->where('ref="'.$result['ref'].'"')->execute();
				}
			}
		}
	}
	
	/**
	 * Passage d'un item en vendu
	 * 
	 * @param string $ref  : La référence de l'item
	 * @param string $type : Le type d'achat
	 * @param int    $po   : Les po obtenu
	 * 
	 * @return bool
	 */
	public function setVendu($ref, $type, $po)
	{
		$default = false;
		
		if(!is_string($ref) || !is_string($type) || !is_int($po))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$dateNow = new \BFW\CKernel\Date;
		
		$data = array(
			'vendu' => 1,
			'typeVente' => '"'.$type.'"',
			'dateVendu' => '"'.$dateNow->getSql().'"',
			'poGagne' => $po
		);
		$req = $this->update($this->_name, $data)->where('ref=:ref', array(':ref' => $ref));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Remise en vente d'un item
	 * 
	 * @param string $ref      : La ref de l'item
	 * @param int    $enchere  : La valeur pour un achat en enchère
	 * @param int    $rachat   : La valeur pour un achat en rachat
	 * @param int    $Uenchere : Le prix unité en enchère
	 * @param int    $Urachat  : Le prix unité en rachat
	 * @param int    $Unb      : Le nombre d'unité
	 * @param string $date     : La date de début de mise en vente
	 * @param int    $duree    : La durée de mise en vente
	 * 
	 * @return bool
	 */
	public function setVente($ref, $enchere, $rachat, $Uenchere, $Urachat, $Unb, $date, $duree)
	{
		$default = false;
		
		$dataVerif = verifTypeData(array(
			array('type' => 'string', 'data' => $ref),
			array('type' => 'int', 'data' => $enchere),
			array('type' => 'int', 'data' => $rachat),
			array('type' => 'int', 'data' => $Uenchere),
			array('type' => 'int', 'data' => $Urachat),
			array('type' => 'int', 'data' => $Unb),
			array('type' => 'string', 'data' => $date),
			array('type' => 'int', 'data' => $duree)
		));
		
		if(!$dataVerif)
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$date = new \BFW\CKernel\Date($date);
		
		$data = array(
			'enVente' => 1,
			'enchere' => $enchere,
			'rachat' => $rachat,
			'enchere_unite' => $Uenchere,
			'rachat_unite' => $Urachat,
			'nb_piece' => $Unb,
			'dateDebut' => '"'.$date->getSql().'"',
			'duree' => $duree
		);
		$req = $this->update($this->_name, $data)->where('ref=:ref', array(':ref' => $ref));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}

	/**
	 * Permet de recherche un item
	 * 
	 * @param string item : L'id de l'item qu'on recherche (avec son typem)
	 * 
	 * @return array()
	 */
	public function search($idItem)
	{
		$default = array();
		if(!is_string($idItem))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné');}
			else {return $default;}
		}
		
		$typeItem = substr($idItem, 0, 1);
		$idItem = substr($idItem, 1);
		
		$req = $this->select()
					->from($this->_name, 'ref')
					->where('idItem=:idItem', array(':idItem' => $idItem))
					->where('typeItem=:typeItem', array(':typeItem' => $typeItem));
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Mise à jour de l'id de l'item utilisé
	 * 
	 * @param string $ref     : La référence de l'item à mettre à jour
	 * @param string $newItem : L'id du nouvelle item (avec son type)
	 * 
	 * @return bool
	 */
	public function majIdItem($ref, $newItem)
	{
		$default = false;
		if(!is_string($ref) || !is_string($newItem))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres donnés');}
			else {return $default;}
		}
		
		//Calcul de la nouvelle ref
		$req = $this->select()->from($this->_name, array('idUser', 'idPerso'))->where('ref=:ref', array(':ref' => $ref));
		$resInfos = $req->fetchRow();
		if(!$resInfos) {return $default;}
		
		$typeItem = substr($newItem, 0, 1);
		$idItem = substr($newItem, 1);
		
		$req = $this->select()
					->from($this->_name, array('ref'))
					->where('idUser=:user', array(':user' => $resInfos['idUser']))
					->where('idPerso=:perso', array(':perso' => $resInfos['idPerso']))
					->where('idItem=:item', array(':item' => $idItem));
		$res = $req->fetchAll();
		
		$nb = 1;
		if($res)
		{
			foreach($res as $result)
			{
				$num = (int) substr($result['ref'], (strpos($result['ref'], 'N')+1));
				if($num >= $nb) {$nb = $num+1;}
			}
		}
		
		$newRef = 'U'.$resInfos['idUser'].'P'.$resInfos['idPerso'].$typeItem.$idItem.'N'.$nb;
		
		//maj table perso_item
		$data = array(
			'ref' => '"'.$newRef.'"',
			'idItem' => $idItem,
			'typeItem' => '"'.$typeItem.'"'
		);
		$update = $this->update($this->_name, $data)->where('ref=:ref', array(':ref' => $ref));
		
		//maj table perso_item_stat
		$MPIStat = new \modeles\PersoItemStat;
		if($MPIStat->maj($ref, $newRef))
		{
			if($update->execute()) {return true;}
			else {$MPIStat->maj($newRef, $ref);}
		}
		return $default;
	}

	/**
	 * Retourne les infos sur un seul item en particulier
	 * 
	 * @param string $ref : La référence de l'item dans la table perso_item
	 * 
	 * @return array
	 */
	public function getPersoItem($ref)
	{
		$default = array();
		if(!is_string($ref))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name)->where('ref=:ref', array(':ref' => $ref));
		$res = $req->fetchRow();
		
		if(!$res) {return $default;}
		else {return $res;}
	}
	
	/**
	 * Déplace un item d'un perso à un autre
	 * 
	 * @param string $ref     : La référence de l'item dans la table perso_item
	 * @param int    $idPerso : L'id du nouveau perso
	 */
	public function movePerso($ref, $idPerso)
	{
		$default = false;
		if(!is_string($ref) || !is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres donnés');}
			else {return $default;}
		}
		
		//Calcul de la nouvelle ref
		$req = $this->select()->from($this->_name, array('idUser', 'idItem', 'typeItem'))->where('ref=:ref', array(':ref' => $ref));
		$resInfos = $req->fetchRow();
		if(!$resInfos) {return $default;}
		
		$req = $this->select()
					->from($this->_name, array('ref'))
					->where('idUser=:user', array(':user' => $resInfos['idUser']))
					->where('idPerso=:perso', array(':perso' => $idPerso))
					->where('idItem=:item', array(':item' => $resInfos['idItem']));
		$res = $req->fetchAll();
		
		$nb = 1;
		if($res)
		{
			foreach($res as $result)
			{
				$num = (int) substr($result['ref'], (strpos($result['ref'], 'N')+1));
				if($num >= $nb) {$nb = $num+1;}
			}
		}
		
		$newRef = 'U'.$resInfos['idUser'].'P'.$idPerso.$resInfos['typeItem'].$resInfos['idItem'].'N'.$nb;
		
		//maj table perso_item
		$data = array(
			'ref' => '"'.$newRef.'"',
			'idPerso' => $idPerso
		);
		$update = $this->update($this->_name, $data)->where('ref=:ref', array(':ref' => $ref));
		
		//maj table perso_item_stat
		$MPIStat = new \modeles\PersoItemStat;
		if($MPIStat->maj($ref, $newRef))
		{
			if($update->execute()) {return true;}
			else {$MPIStat->maj($newRef, $ref);}
		}
		return $default;
	}

	/**
	 * Modifie les prix d'un item
	 * 
	 * @param string $ref      : La référence de l'item dans la table perso_item
	 * @param int    $enchere  : Le prix en enchère
	 * @param int    $rachat   : Le prix en rachat
	 * @param int    $Uenchere : Le prix unité en enchère
	 * @param int    $Urachat  : Le prix unité en rachat
	 * @param int    $Unb      : Le nombre d'unité
	 * 
	 * @return bool
	 */
	public function editVente($ref, $enchere, $rachat, $Uenchere, $Urachat, $Unb)
	{
		$default = false;
		
		$dataVerif = verifTypeData(array(
			array('type' => 'string', 'data' => $ref),
			array('type' => 'int', 'data' => $enchere),
			array('type' => 'int', 'data' => $rachat),
			array('type' => 'int', 'data' => $Uenchere),
			array('type' => 'int', 'data' => $Urachat),
			array('type' => 'int', 'data' => $Unb)
		));
		
		if(!$dataVerif)
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$data = array(
			'enchere' => $enchere,
			'rachat' => $rachat,
			'enchere_unite' => $Uenchere,
			'rachat_unite' => $Urachat,
			'nb_piece' => $Unb
		);
		
		$req = $this->update($this->_name, $data)->where('ref=:ref', array(':ref' => $ref));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}

	/**
	 * Supprime un item
	 * 
	 * @param string $ref : La référence de l'item dans la table perso_item
	 * 
	 * @return bool
	 */
	public function supprItem($ref)
	{
		$default = false;
		if(!is_string($ref))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné');}
			else {return $default;}
		}
		
		$req = $this->delete($this->_name)->where('ref=:ref', array(':ref' => $ref));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
}
?>