<?php

namespace modeles;

/**
 * Modèle pour la table item
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class Item extends \BFW_Sql\Classes\Modeles
{
	protected $_name = 'item';
	
	/**
	 * Recherche un item avec un nom commençant par le paramètre. Maximum 10 item retourné en ordre alphabétique.
	 * 
	 * @param string $search : Le mot clé à rechercher
	 * @param bool   $exact  : Indique si on cherche exactement ce mot-là ou pas
	 * 
	 * @return array();
	 */
	public function search($search, $exact=false, $onlyStart=false)
	{
		$default = array();
		
		if(!is_string($search) || !is_bool($exact))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, array('id', 'text', 'color'))
					->order('`text` ASC')
					->limit(array(0, 15));
					
			if($exact)     {$req->where('`text`=:search', array(':search' => $search));}
		elseif($onlyStart) {$req->where('`text` LIKE ?', array($search.'%'));}
		else               {$req->where('`text` LIKE ?', array('%'.$search.'%'));}
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}

	/**
	 * Récupère tous les items existant
	 * 
	 * @param int $idStart : L'id à partir duquel on doit commencer à récupérer
	 * 
	 * @return array
	 */
	public function getAll($idStart)
	{
		$req = $this->select()
					->from($this->_name, array('id', 'text'))
					->where('id>=:id', array(':id' => $idStart))
					->order('id ASC')
					->group('`text`');
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return array();}
	}
	
	/**
	 * Supprime un item
	 * 
	 * @param int idItem : L'id de l'item à supprimer
	 * 
	 * @return bool
	 */
	public function suppr($idItem)
	{
		$default = false;
		if(!is_int($idItem))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné.');}
			else {return $default;}
		}
		
		$suppr = $this->delete($this->_name)->where('id=:id', array(':id' => $idItem));
		
		if($suppr->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Met à jour un item
	 * 
	 * @param int   $idItem : L'id de l'item
	 * @param array $data   : Les infos sur l'item
	 * 
	 * @return bool
	 */
	public function maj($idItem, $data)
	{
		$default = false;
		if(!is_int($idItem) || !is_array($data))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné.');}
			else {return $default;}
		}
		
		$dataVerif = verifTypeData(array(
			array('type' => 'int', 'data' => $data['id']),
			array('type' => 'string', 'data' => $data['left']),
			array('type' => 'string', 'data' => $data['right']),
			array('type' => 'string', 'data' => $data['text']),
			array('type' => 'string', 'data' => $data['color'])
		));
		
		if(!$dataVerif) {return false;}
		
		$req = $this->update($this->_name, $data)->where('id=:id', array(':id' => $idItem));
		if($req->execute()) {return true;}
		else {return false;}
	}
	
	/**
	 * Créer l'item
	 * 
	 * @param array $data : Les infos sur l'item
	 * 
	 * @return bool
	 */
	public function create($data)
	{
		$default = false;
		if(!is_array($data))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné.');}
			else {return $default;}
		}
		
		$dataVerif = verifTypeData(array(
			array('type' => 'int', 'data' => $data['id']),
			array('type' => 'string', 'data' => $data['left']),
			array('type' => 'string', 'data' => $data['right']),
			array('type' => 'string', 'data' => $data['text']),
			array('type' => 'string', 'data' => $data['color'])
		));
		
		if(!$dataVerif) {return false;}
		
		$req = $this->insert($this->_name, $data);
		if($req->execute()) {return true;}
		else {return false;}
	}
	
	/**
	 * Vérifie si un item existe
	 * 
	 * @param int $idItem : L'id de l'item recherché
	 * 
	 * @return bool/null
	 */
	public function ifExists($idItem)
	{
		$default = null;
		if(!is_int($idItem))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donné.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'id')
					->where('id=:id', array(':id' => $idItem));
		$res = $req->fetchRow();
		
		if(!$res && $req->nb_result() == 0) {return false;}
		else {return true;}
	}
}
?>