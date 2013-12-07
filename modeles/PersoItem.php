<?php

namespace modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class PersoItem extends \BFW_Sql\Classes\Modeles
{
	/**
	 * Nom de la table
	 */
	protected $_name = 'perso_item';
	
	/**
	 * Retourne l'instance Sql_Select de la requête comptant le nombre d'item en vente pour un perso
	 * 
	 * @param string $idPerso : Le champ sur lequel est mit l'id du perso (ex: p.idPerso)
	 * 
	 * @return ressource/null : La ressource Sql_Select, null si erreur sur le paramètre.
	 */
	public function subQ_nbVente($idPerso)
	{
		$default = null;
		if(!is_string($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Paramètre idPerso incorrecT.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'COUNT(idItem)')
					->where('idPerso='.$idPerso)
					->where('vendu=0')
					->where('enVente=1');
		return $req;
	}
	
	/**
	 * Retourne l'instance Sql_Select de la requête comptant le nombre d'item en attente pour un perso
	 * 
	 * @param string $idPerso : Le champ sur lequel est mit l'id du perso (ex: p.idPerso)
	 * 
	 * @return ressource/null : La ressource Sql_Select, null si erreur sur le paramètre.
	 */
	public function subQ_nbWait($idPerso)
	{
		$default = null;
		if(!is_string($idPerso))
		{
			if($this->get_debug()) {throw new Exception('Paramètre idPerso incorrecT.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, 'COUNT(idItem)')
					->where('idPerso='.$idPerso)
					->where('vendu=0')
					->where('enVente=0');
		return $req;
	}
	
	/**
	 * Retourne la valeur des ventes de la semaine pour un User
	 * 
	 * @param int  $idUser  : L'id de l'user
	 * @param Date $dateDeb : L'objet Date de la date de début de la semaine
	 * @param Date $dateFin : L'objet Date de la date de fin de la semaine
	 * 
	 * @return int : Le nombre de po gagné dans la semaine
	 */
	public function getPoVenteSemaine($idUser, $dateDeb, $dateFin)
	{
		$default = 0;
		
		$classDateDeb = get_class($dateDeb);
		$classDateFin = get_class($dateFin);
		
		if($classDateDeb != 'BFW\CKernel\Date' || $classDateFin != 'BFW\CKernel\Date' || !is_int($idUser))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans les paramètres données.');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from($this->_name, array('po' => 'SUM(poGagne)'))
					->where('idUser=:user', array(':user' => $idUser))
					->where('dateVendu >= "'.$dateDeb->getSql().'"')
					->where('dateVendu <= "'.$dateFin->getSql().'"');
		$res = $req->fetchRow();
		
		if($res) {return (int) $res['po'];}
		else {return $default;}
	}
}
?>