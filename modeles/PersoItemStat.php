<?php

namespace modeles;

/**
 * Modèle pour la table item
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class PersoItemStat extends \BFW_Sql\Classes\Modeles
{
	protected $_name = 'perso_item_stat';
	
	/**
	 * Ajoute une stat à un item d'un perso
	 * 
	 * @param string $ref   : La ref de l'item
	 * @param array  $stats : Les stats à ajouter
	 * 
	 * @return bool
	 */
	public function add($ref, $stats)
	{
		//Les vérifs sont déjà fait car on vient de PersoItem->add() uniquement.
		
		$retour = true;
		foreach($stats as $stat)
		{
			$stat = (int) substr($stat, 1);
			$req = $this->insert($this->_name, array('ref' => $ref, 'idStat' => $stat));
			
			if(!$req->execute()) {$retour = false;}
		}
		
		return $retour;
	}
	
	/**
	 * Retourne les stats (avec leurs nom) d'un item via sa ref
	 * 
	 * @param string $ref : la ref de l'item dans perso_item
	 * 
	 * @return array
	 */
	public function getStats($ref)
	{
		$default = array();
		if(!is_string($ref))
		{
			if($this->get_debug()) {throw new Exception('Erreur dans le paramètre donnée');}
			else {return $default;}
		}
		
		$req = $this->select()
					->from(array('pis' => $this->_name), 'idStat')
					->join(array('s' => 'stat'), 's.idStat=pis.idStat', array('nom'))
					->where('pis.ref=:ref', array(':ref' => $ref));
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return $default;}
	}
}
?>