<?php
$step = (int) get(0);

if($step == 1) //Sauvegarde la base de données actuelle
{
	require_once(path_modules.'maj/step/step1.php');
}
elseif($step == 2) //Supression des items en doublon
{
	require_once(path_modules.'maj/step/step2.php');
}
elseif($step == 3) //Supression des runes en doublon
{
	//Pas besoin, j'avais déjà géré le cas lors de leurs premier import
}
elseif($step == 4) //Récupération du fichier de donnée
{
	ErrorView(500);
}
elseif($step == 5) //Extraction des fichiers de donnée
{
	
}
elseif($step == 6) //Extraction des items
{
	
}
elseif($step == 7) //Sauvegarde des items
{
	
}
elseif($step == 8) //Extraction des runes et stat
{
	
}
elseif($step == 9) //Sauvegarde des runes et stat
{
	
}
elseif($step == 10) //Mise à jour de la configuration
{
	
}
?>