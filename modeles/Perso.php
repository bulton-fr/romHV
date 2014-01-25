<?php

namespace modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class Perso extends \BFW_Sql\Classes\Modeles
{
	/**
	 * Nom de la table
	 */
	protected $_name = 'perso';
	
	/**
	 * Retourne tous les persos pour un id d'user donnée
	 * 
	 * @param int $idUser : L'id de l'user des persos
	 * 
	 * @return array : La liste des comptes
	 */
	public function getAll($idUser)
	{
		$default = array();
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name)->where('idUser=:id', array(':id' => $idUser))->order('idPerso ASC');
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Retourne tous les persos pour un id d'user donnée avec les informations sur ces ventes
	 * 
	 * @param int $idUser : L'id de l'user des persos
	 * 
	 * @return array : La liste des comptes
	 */
	public function getAllWithVente($idUser)
	{
		$default = array();
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$MPersoItem = new \modeles\PersoItem;
		$sub_nbVente = $MPersoItem->subQ_nbVente('p.idPerso');
		$sub_nbWait = $MPersoItem->subQ_nbWait('p.idPerso');
		
		$req = $this->select()
					->from(array('p' => $this->_name))
					->where('idUser=:id', array(':id' => $idUser))
					->order('idPerso ASC');
					
		if(!is_null($sub_nbVente)) {$req->subQuery($sub_nbVente, 'nbVente');}
		if(!is_null($sub_nbWait)) {$req->subQuery($sub_nbWait, 'nbWait');}
		
		$res = $req->fetchAll();
		
		if($res)
		{
			if(is_null($sub_nbVente)) {$res['nbVente'] = 'Crash';}
			if(is_null($sub_nbWait)) {$res['nbWait'] = 'Crash';}
			
			return $res;
		}
		else {return $default;}
	}
	
	/**
	 * Retourne les informations sur un seul perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * 
	 * @return array : Les infos sur le perso
	 */
	public function getPerso($idPerso)
	{
		$default = array();
		if(!is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name)->where('idPerso=:id', array(':id' => $idPerso));
		$res = $req->fetchRow();
		
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Met à jour le nom d'un perso
	 * 
	 * @param int    $idPerso : L'id du perso
	 * @param string $nom     : Le nouveau nom du perso
	 * 
	 * @return bool
	 */
	public function setNom($idPerso, $nom)
	{
		if(!is_int($idPerso) || !is_string($nom))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->update($this->_name, array('nom' => '"'.$nom.'"'))->where('idPerso=:id', array(':id' => $idPerso));
		
		if($req->execute()) {return true;}
		else {return false;}
	}
    
    /**
     * Récupère le nombre de po d'un perso
     * 
     * @param int $idPerso : L'id du perso
     * 
     * @return int
     */
    public function getPo($idPerso)
    {
        $default = 0;
        if(!is_int($idPerso))
        {
            if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
            else {return $default;}
        }
        
        $req = $this->select()->from($this->_name, array('po'))->where('idPerso=:id', array(':id' => $idPerso));
        $res = $req->fetchRow();
        
        if($res) {return (int) $res['po'];}
        else {return $default;}
    }
	
	/**
	 * Met à jour le nombre de po d'un perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * @param int $po      : Le nouveau nombre de po
	 * 
	 * @return bool
	 */
	public function setPo($idPerso, $po)
	{
		if(!is_int($idPerso) || !is_int($po))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->update($this->_name, array('po' => $po))->where('idPerso=:id', array(':id' => $idPerso));
		
		if($req->execute())
		{
			global $idUser;
			$MUser = new \modules\users\modeles\Users;
			return $MUser->recalculPo($idUser);
		}
		else {return false;}
	}
	
	/**
	 * Créer un nouveau perso
	 * 
	 * @param int    $idUser : L'id de l'user
	 * @param string $nom    : Le nom du perso
	 * @param int    $po     : Le nombre de po
	 * 
	 * @return bool
	 */
	public function create($idUser, $nom, $po)
	{
		if(!is_int($idUser) || !is_string($nom) || !is_int($po))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$data = array(
			'idUser' => $idUser,
			'nom' => $nom,
			'po' => $po
		);
		
		$req = $this->insert($this->_name, $data);
		
		if($req->execute())
		{
			global $idUser;
			$MUser = new \modules\users\modeles\Users;
			return $MUser->recalculPo($idUser);
		}
		else {return false;}
	}
	
	/**
	 * Supprime un perso
	 * 
	 * @param int $idPerso : L'id du perso
	 * 
	 * @return bool
	 */
	public function remove($idPerso)
	{
		if(!is_int($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->delete($this->_name)->where('idPerso=:id', array(':id' => $idPerso));
		
		if($req->execute())
        {
            global $idUser;
            $MUser = new \modules\users\modeles\Users;
            return $MUser->recalculPo($idUser);
        }
		else {return false;}
	}
	
	/**
	 * Retourne la valeur des ventes de la semaine pour un User
	 * 
	 * @param int  		$idUser : L'id de l'user
	 * @param SqlSelect $subReq : L'objet SqlSelect pour la sous-requête
	 * 
	 * @return array : Le nombre de po gagné dans la semaine pour chaque perso de l'user
	 */
	public function getReqPoVenteSemaineAllPerso($idUser, $subReq)
	{
		$default = array();
		$classSubReq = get_class($subReq);
		
		if($classSubReq != 'BFW_Sql\Classes\SqlSelect' || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		
		$req = $this->select()
					->from(array('p' => $this->_name), '')
					->subQuery($subReq, 'po')
					->where('p.idUser=:user', array(':user' => $idUser))
					->order('idPerso ASC');
		
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
	
	/**
	 * Indique si un perso appartient à un user
	 * 
	 * @param int $idPerso : L'id du perso
	 * @param int $idUSer  : L'id de l'user
	 * 
	 * @return bool
	 */
	public function persoBeUser($idPerso, $idUser)
	{
		$default = false;
		
		if(!is_int($idPerso) || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'idPerso')
					->where('idPerso=:perso', array(':perso' => $idPerso))
					->where('idUser=:user', array(':user' => $idUser));
		$res = $req->fetchRow();
		$nb = $req->nb_result();
		
		if($nb > 0) {$nb = 1;}
		return $nb;
	}
}
?>