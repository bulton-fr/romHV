<?php
//Supression des items en doublon
$list_suppr = array();

//Récupérer la liste actuelle
$MItem = new \modeles\Item;
$MPersoItem = new \modeles\PersoItem;
$listItem = $MItem->getAll();

foreach($listItem as $item)
{
	//Si élément déjà supprimé, on passe.
	if(!in_array($item['id'], $list_suppr)) //Petite sécurité mais normalement pas besoin car un group by est dans la requête
	{
		//Récupérer liste des doublons
		$listDoublon = $MItem->search($item['text'], true);
		
		if(count($listDoublon) > 1)
		{
			foreach($listDoublon as $doublon)
			{
				if($doublon['id'] != $item['id'])
				{
					//Pour chaque éléments vérifier s'il n'est pas utilisé dans perso_item
					$listPersoItem = $MPersoItem->search('I'.$doublon['id']);
					
					if(count($listPersoItem) > 0)
					{
						foreach($listPersoItem as $itemUseDoublon)
						{
							//S'il est utilisé, remplacer par l'id de l'item qu'on a et maj de la ref
							if(!$MPersoItem->majIdItem($itemUseDoublon['ref'], 'I'.$item['id'])) {ErrorView(500);}
						}
					}
					
					//Supprimer les doublons trouvé et gardé en mémoire l'id des items supprimé
					$DoublonId = (int) $doublon['id'];
					if($MItem->suppr($DoublonId)) {$list_suppr[] = $doublon['id'];}
					else {ErrorView(500);}
				}
			}
		}
	}
}