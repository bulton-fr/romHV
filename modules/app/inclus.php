<?php
//Autoloader classes
$ClassLoaderModule_App = new SplClassLoader('app\Classes', $path.'modules/app/classes');
$ClassLoaderModule_App->register();

//Autoloader interfaces
$InterLoaderModule_App = new SplClassLoader('app\Interfaces', $path.'modules/app/interfaces');
$InterLoaderModule_App->register();


//Inclusion fonction
$dir = opendir(path_modules.'app/fonctions'); //Ouverture du dossier fonctions se trouvant à la racine
$dir_arr = array('.', '..'); //Les fichiers & dossiers à ignorer à la lecture

while(false !== ($file = readdir($dir))) //Si on a un fichier
{
	//Si c'est un fichier, et que ce n'est pas une sauvegarde auto, on inclu.
	if(!in_array($file, $dir_arr) && !preg_match("#~$#", $file))
	{
		require_once(path_modules.'app/fonctions/'.$file);
	}
}

closedir($dir); //Fermeture du dossier
unset($dir, $dir_arr, $file); //Suppression des variables
//Fin Inclusion fonction
?>