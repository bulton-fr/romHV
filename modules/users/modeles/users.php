<?php
namespace modules\users\modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class users extends \BFW_Sql\Classes\Modeles
{
	/**
	 * Nom de la table
	 */
	protected $_name = 'users';
	
	/**
	 * Retourne l'id pour un login donné
	 * 
	 * @param string $login : Le login
	 * 
	 * @return int : L'id trouvé. 0 si aucun login trouvé.
	 */
	public function IdFromLogin($login)
	{
		$default = 0;
		if(!is_string($login))
		{
			if($this->get_debug()) {new Exception('Le login donné en paramètre doit être de type string.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'id')->where('login=:login', array(':login' => $login));
		$res = $req->fetchrow();
		
		if($res) {return $res['id'];}
		else {return $default;}
	}
	
	/**
	 * Retourne le login pour un id donné
	 * 
	 * @param int $id : L'id
	 * 
	 * @return string : Le login correspond. Chaîne vide si non trouvé.
	 */
	public function getLogin($id)
	{
		$default = '';
		if(!is_int($id))
		{
			if($this->get_debug()) {new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'login')->where('id=:id', array(':id' => $id));
		$res = $req->fetchrow();
		
		if($res) {return $res['login'];}
		else {return $default;}
	}
	
	/**
	 * Retourne le mot de passe pour un id donné
	 * 
	 * @param int $id : L'id
	 * 
	 * @return string : Le mot de passe correspond. Chaîne vide si non trouvé.
	 */
	public function getMdp($id)
	{
		$default = '';
		if(!is_int($id))
		{
			if($this->get_debug()) {new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'mdp')->where('id=:id', array(':id' => $id));
		$res = $req->fetchrow();
		
		if($res) {return $res['mdp'];}
		else {return $default;}
	}
	
	/**
	 * Retourne le mail pour un id donné
	 * 
	 * @param int $id : L'id
	 * 
	 * @return string : Le mail correspond. Chaîne vide si non trouvé.
	 */
	public function getMail($id)
	{
		$default = '';
		if(!is_int($id))
		{
			if($this->get_debug()) {new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'mail')->where('id=:id', array(':id' => $id));
		$res = $req->fetchrow();
		
		if($res) {return $res['mail'];}
		else {return $default;}
	}
}
