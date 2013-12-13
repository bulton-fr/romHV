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
	public function search($search, $exact=false)
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
					
		if($exact) {$req->where('`text`=:search', array(':search' => $search));}
		else {$req->where('`text` LIKE ?', array('%'.$search.'%'));}
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}

	/**
	 * Récupère tous les items existant
	 * 
	 * @return array
	 */
	public function getAll()
	{
		$req = $this->select()->from($this->_name, array('id', 'text'))->order('`text` ASC')->group('`text`');
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
}
?>