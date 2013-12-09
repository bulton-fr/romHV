<?php
$list = array();
$match = array();

$fop = fopen(path_modules.'list_item/import/string_fr.db', 'r');
while($line = fgets($fop))
{
	if(preg_match('#^"Sys([0-9]+)_name"="(.+)"$#', $line, $match))
	{
		$id = (int) $match[1];
		$title = $match[2];
		
		if($id >= 510000 && $id < 530000 && $title != 'Sys'.$id.'_name')
		{
			$list[$id] = $title;
		}
	}
}
fclose($fop);

$MStat = new \modeles\Stat;
foreach($list as $id => $nom)
{
	$MStat->create($id, $nom);
}

exit;
?>