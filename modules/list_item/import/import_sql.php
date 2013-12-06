<?php
/**
 * Fichier à lancer en console, en dehors du framework.
 */

//--- PDO ---
include_once('../BFW_Sql/config.php');
$PDO = new PDO($bd_type.':host='.$bd_host.';dbname='.$bd_name, $bd_user, $bd_pass);
$PDO->exec('SET NAMES utf8');
//--- PDO ---


include_once('list_item_saved.php');

$PDO->exec('INSERT INTO `config` SET `ref`="rom_version", `value`="'.$RunesVersion.'"');


foreach($ItemPreview_ListData as $key => $item)
{
	if(is_int($key))
	{
		echo 'item '.$item['id'].' ...';
		
		
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
		
		$data['id'] = $item['id'];
		$data['left'] = $left;
		$data['right'] = $right;
		$data['text'] = $item['text'];
		$data['color'] = $item['color'];
		
		$PDO->exec('
			INSERT INTO `item` SET 
				`id`="'.$data['id'].'", 
				`tip_left`="'.$data['left'].'", 
				`tip_right`="'.$data['right'].'", 
				`text`="'.$data['text'].'", 
				`color`="'.$data['color'].'"
		');
		
		echo " OK\n";
	}
	else
	{
		echo "Pas un item.\n";
	}
}
?>