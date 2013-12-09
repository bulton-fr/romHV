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
			$req = $this->insert($this->_name, array('ref' => $ref, 'idStat' => $stat));
			
			if(!$req->execute()) {$retour = false;}
		}
		
		return $retour;
	}
}
?>