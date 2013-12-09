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
		
		if(!$this->statExists($id, $nom))
		{
			$data = array('idStat' => $id, 'nom' => $nom);
			$req = $this->insert($this->_name, $data);
			
			if($req->execute()) {return true;}
			else {return $default;}
		}
		else {return $default;}
	}
	
	/**
	 * Permet de savoir si une stat existe déjà dans la table
	 * 
	 * @param int    $id  : L'id de la stat
	 * @param string $nom : Le nom de la stat
	 * 
	 * @return bool
	 */
	public function statExists($id, $nom)
	{
		$default = true;
		
		if(!is_int($id) || !is_string($nom))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donnée.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'idStat')
					->where('idStat=:id OR nom=:nom', array(':id' => $id, ':nom' => $nom));
		$res = $req->fetchRow();
		
		if(!$res && $req->nb_result() == 0) {return false;}
		else {return $default;}
	}
	
	/**
	 * Permet de récupérer le nom d'une stat à partir de son id
	 * 
	 * @param int $id : L'id de la stat
	 * 
	 * @return string : Le nom de la stat
	 */
	public function getNomStat($id)
	{
		$default = '';
		
		if(!is_int($id))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donnée.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'nom')->where('idStat=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['nom'];}
		else {return $default;}
	}
	
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
					->from($this->_name, array('idStat', 'nom'))
					->where('`nom` LIKE ?', array('%'.$search.'%'))
					->order('nom ASC')
					->limit(array(0, 15));
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
}
?>