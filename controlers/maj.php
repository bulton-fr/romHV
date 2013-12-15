<?php
$step = (int) get(0);

if($step == 1) //Sauvegarde la base de données actuelle
{
//	require_once(path_modules.'maj/step/step1.php');
}
elseif($step == 2) //Supression des items en doublon
{
//	require_once(path_modules.'maj/step/step2.php');
}
elseif($step == 3) //Supression des runes en doublon
{
//	//Pas besoin, j'avais déjà géré le cas lors de leurs premier import
}
elseif($step == 4) //Récupération du fichier de donnée
{
//	require_once(path_modules.'maj/step/step4.php');
}
elseif($step == 5) //Extraction des fichiers de donnée
{
//	require_once(path_modules.'maj/step/step5.php');
}
elseif($step == 6) //Extraction des items
{
//	require_once(path_modules.'maj/step/step6.php');
}
elseif($step == 7) //Sauvegarde des items
{
	if(function_exists('system'))
	{
		$system = system('cd ../modules/maj/step && php5 step7.php');
		if($system === false) {ErrorView(500);}
	}
	else {ErrorView(500);}
}
elseif($step == 8) //Extraction des runes et stat
{
	ErrorView(500);
}
elseif($step == 9) //Sauvegarde des runes et stat
{
	
}
elseif($step == 10) //Mise à jour de la configuration
{
	
}
elseif($step == 11) //Suppression des fichiers téléchargés
{
	
}
?>