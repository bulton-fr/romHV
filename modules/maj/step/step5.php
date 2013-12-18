<?php
//Extraction des fichiers de donnée

if(!file_exists(path_modules.'temp/import/runes.targz')) {ErrorView(500);}

if(function_exists('system'))
{
	$system = system('cd ../modules/temp/import && tar -zxf runes.targz');
	if($system === false) {ErrorView(500);}
	
	if(!(
		file_exists(path_modules.'temp/import/SaveVariables.lua') && 
		file_exists(path_modules.'temp/import/string_fr.db')
	)) {ErrorView(500);}
}
else {ErrorView(500);}
?>