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
	public function recupAll($idUser)
	{
		$default = array();
		if(!is_int($idUser))
		{
			if($this->get_debug()) {new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name)->where('idUser=:id', array(':id' => $idUser));
		$res = $req->fetchAll();
		
		if($res) {return $res;}
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
		if(!is_int($idUser))
		{
			if($this->get_debug()) {new Exception('L\'id donné en paramètre doit être de type int.');}
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
			if($this->get_debug()) {new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->update($this->_name, array('nom' => $nom))->where('idPerso=:id', array(':id', $idPerso));
		
		if($req->execute()) {return true;}
		else {return false;}
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
			if($this->get_debug()) {new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->update($this->_name, array('po' => $po))->where('idPerso=:id', array(':id', $idPerso));
		
		if($req->execute()) {return true;}
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
			if($this->get_debug()) {new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$data = array(
			'idUser' => $idUser,
			'nom' => $nom,
			'po' => $po
		);
		
		$req = $this->insert($this->_name, $data);
		
		if($req->execute()) {return true;}
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
			if($this->get_debug()) {new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->delete($this->_name)->where('idPerso=:id', array(':id', $idPerso));
		
		if($req->execute()) {return true;}
		else {return false;}
	}
}
?>