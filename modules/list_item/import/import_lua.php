<?php
/**
 * Fichier à lancer en console, en dehors du framework.
 */
//ItemPreview_ListData
//ItemPreview_Settings

function replace($line, $pattern, $replace)
{
	if(preg_match($pattern, $line))
	{
		$line = preg_replace($pattern, $replace, $line);
	}
	
	return $line;
}

function traitement($line)
{
	$line = str_replace('=', '=>', $line);
	$line = str_replace('{', 'array (', $line);
	$line = str_replace('}', ')', $line);
	
	$line = replace($line, '/\[([0-9]+)\](.+)/', '$1$2');
	$line = replace($line, '/\["([\w]+)"\](.+)/', '"$1"$2');
	
	
	return $line;
}


$file = 'SaveVariables.lua';
$list = "<?php\n".'$RunesVersion = \'6.0.3.2672\';'."\n";

$fop = fopen($file, 'r');
$replace = false;
$deb_array = false;
$tmp = '';
$mobs = false;

echo "Début du traitement\n";
while($line = fgets($fop))
{
	if(strpos($line, 'ItemPreview_ListData') !== false)
	{
		echo "Début de la liste trouvé\n";
		$replace = true;
		$deb_array = true;
		
		$list .= '$';
	}
	
	if(strpos($line, 'ItemPreview_Settings') !== false)
	{
		echo "Fin de la liste\n";
		$list = substr($list, 0, -2);
		$list .= ";\n?>";
		$replace = false; //Au cas ou !
		break;
	}
	
	if($replace == true)
	{
		echo "Traitement...";
		$tmp = traitement($line);
		echo "Ok / ";
		
		if(strpos($tmp, '"mobs" => array (')) {$mobs = true;}
		if(strpos($tmp, '"color" =>') && $mobs == true) {$mobs = false;}
		
		if($deb_array == true)
		{
			$tmp = str_replace('ItemPreview_ListData => array (', 'ItemPreview_ListData = array (', $tmp);
			$deb_array = false;
		}
		
		if($mobs == false) {$list .= $tmp;}
		
		echo "Ok : $tmp";
	}
}
fclose($fop);
echo "Fin de traitement\nSauvegarde en cours...";

file_put_contents('list_item_saved.php', $list);
echo "Ok\n";
?>