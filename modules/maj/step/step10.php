<?php

$listFile = array(
	'ListeItems.php',
	'SaveVariables.lua',
	'string_fr.db',
	'runes.targz'
);

foreach($listFile as $file)
{
	if(file_exists(path_modules.'temp/import/'.$file))
	{
		if(!unlink(path_modules.'temp/import/'.$file)) {ErrorView(500);}
	}
	else {ErrorView(500);}
}
?>