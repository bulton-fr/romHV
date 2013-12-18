<?php
//Extraction des items

function replace($line, $pattern, $replace)
{
	if(preg_match($pattern, $line))
	{
		$line = preg_replace($pattern, $replace, $line);
	}
	
	return $line;
}

function lua2php($line)
{
	$line = str_replace('=', '=>', $line);
	$line = str_replace('{', 'array (', $line);
	$line = str_replace('}', ')', $line);
	
	$line = replace($line, '/\[([0-9]+)\](.+)/', '$1$2');
	$line = replace($line, '/\["([\w]+)"\](.+)/', '"$1"$2');
	
	return $line;
}

set_time_limit(0);
$romVersion = $Memcache->getVal('MajVersion');
if($romVersion === false) {ErrorView(500);}

$file = path_modules.'temp/import/SaveVariables.lua';
$fileSaved = path_modules.'temp/import/ListeItems.php';

if(!file_exists($file)) {ErrorView(500);}
$fop = fopen($file, 'r');

$fopSaved = fopen($fileSaved, 'w+');
fputs($fopSaved, "<?php\n");

//Initialise
$list = '';
$replace = false;
$deb_array = false;
$tmp = '';
$mobs = false;

$secureTimeStart = time()+(60*2); //2min max

while($line = fgets($fop)) //Lecture du fichier lua
{
	//Sécurité car timeout désactivé.
	$timeNow = time();
	if($timeNow > $secureTimeStart)
	{
		ErrorView(408);
		exit; //Déjà présent dans ErrorView mais au cas où.
	}
	
	
	if(strpos($line, 'ItemPreview_ListData') !== false) //On commence la liste
	{
		$replace = true;
		$deb_array = true;
		
		$list .= '$';
	}
	
	if(strpos($line, 'ItemPreview_Settings') !== false) //On fini la liste
	{
		$list = substr($list, 0, -2);
		$list .= ";\n?>";
		fputs($fopSaved, $list);
		
		$replace = false; //Au cas ou !
		break;
	}
	
	if($replace == true) //Si on est dans la liste
	{
		$tmp = lua2php($line);
		
		if(strpos($tmp, '"mobs" => array (')) {$mobs = true;}
		if(strpos($tmp, '"color" =>') && $mobs == true) {$mobs = false;}
		
		if($deb_array == true)
		{
			$tmp = str_replace('ItemPreview_ListData => array (', 'ItemPreview_ListData = array (', $tmp);
			$deb_array = false;
		}
		
		if($mobs == false)
		{
			$list .= $tmp;
			fputs($fopSaved, $list);
			$list = '';
		}
	}
}

fclose($fop);
fclose($fopSaved);
?>