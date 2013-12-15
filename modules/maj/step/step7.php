<?php

//Inclusion du kernel
$path = '../../../';
require_once($path.'kernel/kernel.php');


$MItem = new \modeles\Item;

require_once(path_modules.'temp/import/ListeItems.php');

if(!is_array($ItemPreview_ListData)) {ErrorView(500);}

set_time_limit(0);
$secureTimeStart = time()+(60*4); //2min max

foreach($ItemPreview_ListData as $key => $item)
{
	//Sécurité car timeout désactivé.
	$timeNow = time();
	if($timeNow > $secureTimeStart)
	{
		ErrorView(408);
		exit; //Déjà présent dans ErrorView mais au cas où.
	}
	
	
	if(is_int($key))
	{
		$data = array();
		
		if(isset($item['tip']))
		{
			if(is_array($item['tip']['left']))
			{
				ksort($item['tip']['left']);
				$left = implode("\n", $item['tip']['left']);
			}
			else {$left = $item['tip']['left'];}
			
			if(is_array($item['tip']['right']))
			{
				ksort($item['tip']['right']);
				$right = implode("\n", $item['tip']['right']);
			}
			else {$right = $item['tip']['right'];}
		}
		else {$left = $right = '';}
		
		$data['id'] = (int) $item['id'];
		$data['tip_left'] = addslashes($left);
		$data['tip_right'] = addslashes($right);
		$data['text'] = addslashes($item['text']);
		$data['color'] = $item['color'];
		
		$searchItem = count($MItem->search($data['text'], true));
		
		if($searchItem > 0)
		{
			//Gestion des doublons supprimés
			$ifExist = $MItem->ifExists($data['id']);
			if($ifExist === null) {ErrorView(500, false);}
			
			if($ifExist == true)
			{
				if(!$MItem->maj($data['id'], $data))
				{
					echo 'Erreur MAJ de l\'item '.$data['id']."\n";
					var_dump($data);
					ErrorView(500, false);
				}
			}
		}
		else
		{
			if(!$MItem->create($data)) {ErrorView(500, false);}
		}
	}
}
?>