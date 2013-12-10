<?php

namespace modeles;

/**
 * Modèle pour la table config
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class Config extends \BFW_Sql\Classes\Modeles
{
	/**
	 * Nom de la table
	 */
	protected $_name = 'config';
	
	/**
	 * Retourne toutes les configs
	 * 
	 * @return array : La liste des config
	 */
	public function getAll()
	{
		$req = $this->select()->from($this->_name);
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return array();}
	}
	
	/**
	 * Met à jour une config
	 * 
	 * @param string $ref   : La référence de la config
	 * @param string $value : La nouvelle valeur de la config
	 * 
	 * @return bool
	 */
	public function maj($ref, $value)
	{
		if(!is_string($ref) || !is_string($value))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données ne sont pas correct.');}
			else {return false;}
		}
		
		$req = $this->update($this->_name, array('value' => $value))->where('ref=:ref', array(':ref' => $ref));
		
		if($req->execute()) {return true;}
		else {return false;}
	}
	
	/**
	 * Récupère une config
	 * 
	 * @param string $ref : La référence de la config 
	 * 
	 * @return string $value : La valeur de la config
	 */
	public function getConfig($ref)
	{
		$default = '';
		
		if(!is_string($ref))
		{
			if($this->get_debug()) {throw new Exception('Le paramètre données n\'est pas correct.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name)->where('ref="'.$ref.'"');
		$res = $req->fetchRow();
		
		if($res) {return $res['value'];}
		else {return $default;}
	}
}
?>