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
	 * 
	 * @return array();
	 */
	public function search($search)
	{
		$default = array();
		
		if(!is_string($search))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donnée');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, array('id', 'text', 'color'))
					->where('`text` LIKE ?', array('%'.$search.'%'))
					->order('`text` ASC')
					->limit(array(0, 15));
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
}
?>