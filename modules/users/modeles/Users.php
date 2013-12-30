<?php
namespace modules\users\modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class Users extends \BFW_Sql\Classes\Modeles
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
			if($this->get_debug()) {throw new Exception('Le login donné en paramètre doit être de type string.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'id')->where('login=:login', array(':login' => $login));
		$res = $req->fetchrow();
		
		if($res) {return (int) $res['id'];}
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
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'login')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
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
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'mdp')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['mdp'];}
		else {return $default;}
	}
	
	/**
	 * Modifie le mot de passe pour un user donné
	 * 
	 * @param int    $idUser : L'id de l'user
	 * @param string $newMDP : Le nouveau mot de passe
	 * 
	 * @return bool
	 */
	public function setMdp($idUser, $newMDP)
	{
		$default = false;
		
		if(!is_int($idUser) || !is_string($newMDP))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données sont incorrect.');}
			else {return $default;}
		}
		
		$req = $this->update($this->_name, array('mdp' => '"'.$newMDP.'"'))->where('id=:id', array(':id' => $idUser));
		
		if($req->execute()) {return true;}
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
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'mail')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['mail'];}
		else {return $default;}
	}
	
	/**
	 * Retourne le nombre de po pour un id donné
	 * 
	 * @param int $id : L'id
	 * 
	 * @return int : L'id trouvé. 0 si aucun login trouvé.
	 */
	public function getPo($id)
	{
		$default = 0;
		if(!is_int($id))
		{
			if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'po')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['po'];}
		else {return $default;}
	}
	
	/**
	 * Modifie le nombre de po pour un user donné
	 * 
	 * @param int    $idUser : L'id de l'user
	 * @param string $po : Le nouveau nombre de po
	 * 
	 * @return bool
	 */
	public function setPo($idUser, $po)
	{
		$default = false;
		
		if(!is_int($idUser) || !is_int($po))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données sont incorrect.');}
			else {return $default;}
		}
		
		$req = $this->update($this->_name, array('po' => $po))->where('id=:id', array(':id' => $idUser));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Recalcul les po d'un user
	 * 
	 * @param int $idUser : L'id de l'user
	 * 
	 * @return bool
	 */
	public function recalculPo($idUser)
	{
		$default = false;
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur sur l\'id donnée en paramètre.');}
			else {return $default;}
		}
		
		$MPerso = new \modeles\Perso;
		$persos = $MPerso->getAll($idUser);
		
		$po = 0;
		foreach($persos as $perso) {$po += $perso['po'];}
		
		return $this->setPo($idUser, $po);
	}
	
	/**
	 * Récupère la valeur de la couleur de fond
	 * 
	 * @param int $idUser : L'id de l'user
	 * 
	 * @return string
	 */
	public function getBackgroundColor($idUser)
	{
		$default = false;
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur sur l\'id donnée en paramètre.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'backgroundColor')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['backgroundColor'];}
		else {return $default;}
	}
	
	/**
	 * Change la valeur de la couleur de fond
	 * 
	 * @param int $idUser : L'id de l'user
	 * @param string $color : la nouvelle couleur
	 * 
	 * @return bool
	 */
	public function setBackgroundColor($idUser, $color)
	{
		$default = false;
		
		if(!is_int($idUser) || !is_string($color))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données sont incorrect.');}
			else {return $default;}
		}
		
		$req = $this->update($this->_name, array('backgroundColor' => $color))->where('id=:id', array(':id' => $idUser));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Récupère la valeur de l'opacité du fond
	 * 
	 * @param int $idUser : L'id de l'user
	 * 
	 * @return string
	 */
	public function getBackgroundOpacity($idUser)
	{
		$default = false;
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur sur l\'id donnée en paramètre.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'backgroundOpacity')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['backgroundOpacity'];}
		else {return $default;}
	}
	
	/**
	 * Change la valeur de l'opacité du fond
	 * 
	 * @param int $idUser : L'id de l'user
	 * @param int $opacity : la nouvelle opacité
	 * 
	 * @return bool
	 */
	public function setBackgroundOpacity($idUser, $opacity)
	{
		$default = false;
		
		if(!is_int($idUser) || !is_int($opacity))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données sont incorrect.');}
			else {return $default;}
		}
		
		$req = $this->update($this->_name, array('backgroundOpacity' => $opacity))->where('id=:id', array(':id' => $idUser));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
	
	/**
	 * Récupère la valeur du texte noir ou non
	 * 
	 * @param int $idUser : L'id de l'user
	 * 
	 * @return string
	 */
	public function getColorTextBlack($idUser)
	{
		$default = true;
		if(!is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur sur l\'id donnée en paramètre.');}
			else {return $default;}
		}
		
		$req = $this->select()->from($this->_name, 'textColorBlack')->where('id=:id', array(':id' => $id));
		$res = $req->fetchRow();
		
		if($res) {return $res['textColorBlack'];}
		else {return $default;}
	}
	
	/**
	 * Change la valeur du texte noir ou non
	 * 
	 * @param int $idUser : L'id de l'user
	 * @param bool $textBlack : la nouvelle valeur
	 * 
	 * @return bool
	 */
	public function setColorTextBlack($idUser, $textBlack)
	{
		$default = false;
		
		if(!is_int($idUser) || !is_bool($textBlack))
		{
			if($this->get_debug()) {throw new Exception('Les paramètres données sont incorrect.');}
			else {return $default;}
		}
		
		$req = $this->update($this->_name, array('textColorBlack' => $textBlack))->where('id=:id', array(':id' => $idUser));
		
		if($req->execute()) {return true;}
		else {return $default;}
	}
}
