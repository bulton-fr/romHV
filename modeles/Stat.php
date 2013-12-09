<?php

namespace modeles;

/**
 * Modèle pour la table stat
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class Stat extends \BFW_Sql\Classes\Modeles
{
	protected $_name = 'stat';
	
	/**
	 * Permet d'ajouter une stat en bdd
	 * 
	 * @param int    $id  : L'id de la stat
	 * @param string $nom : Le nom de la stat
	 * 
	 * @return bool
	 */
	public function create($id, $nom)
	{
		$default = false;
		if(!is_int($id) || !is_string($nom))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$data = array('idStat' => $id, 'nom' => $nom);
		$req = $this->insert($this->_name, $data);
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Permet de savoir si une stat existe déjà dans la table
	 * 
	 * @param int $id : L'id de la stat
	 * 
	 * @return bool
	 */
	public function statExists($id)
	{
		$default = false;
		
		if(!is_int($id))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donnée.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'id')->where('idStat=:id', array(':id' => $id));
		$res = $this->fetchRow();
		
		if($res)
		{
			$nb = $req->nb_result();
			return ($nb > 0) ? true : $default;
		}
		else {return $default;}
	}
}
?>