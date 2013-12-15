<?php
//Récupération du fichier de donnée

$urlGZip = $Memcache->getVal('MajUrlGZip');

if(function_exists('system') && !empty($urlGZip))
{
	if(file_exists(path_modules.'temp/import/runes.targz'))
	{
		if(!unlink(path_modules.'temp/import/runes.targz')) {ErrorView(500);}
	}
	
	$system = system('cd ../modules/temp/import && wget --no-check-certificate '.$urlGZip);
	if($system === false) {ErrorView(500);}
	
	if(!file_exists(path_modules.'temp/import/runes.targz')) {ErrorView(500);}
}
else {ErrorView(500);}
?>