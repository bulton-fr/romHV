<?php
/**
 * Gère tout le noyau du framework. Est appelé sur chaque page.
 * @author Vermeulen Maxime
 * @package BFW
 */

ob_start(); //Tamporisation du tampon de sortie html. Permet que le code html ne sorte qu'à la fin et non petit à petit (permet d'utiliser les fonctions changeant les headers ou cookie à n'importe quel moment par exemple)

session_set_cookie_params(0); //permet de detruire le cookie de session si le navigateur quitte
session_start(); //Ouverture des sessions

//Définition des chemins d'accès
if(!isset($path))
{
	$path = '../';
}
if(strpos($base_url, -1) == '/')
{
	$base_url = strpos($base_url, 0, -1);
}

//Class Loader
require_once($path.'kernel/classes/SplClassLoader.php');
$ClassLoaderCKernel = new SplClassLoader('BFW\CKernel', $path.'kernel/classes');
$ClassLoaderCKernel->register();

$ClassLoaderIKernelI = new SplClassLoader('BFW\IKernel', $path.'kernel/interfaces');
$ClassLoaderIKernelI->register();

$ClassLoaderModule = new SplClassLoader('modules', $path.'modules');
$ClassLoaderModule->register();

$ClassLoaderModele = new SplClassLoader('modeles', $path.'modeles');
$ClassLoaderModele->register();
//Class Loader


//Inclusion de la classe Kernel
require_once($path.'kernel/classes/Kernel.php');

//Instancie la classe Kernel
$Kernel = new BFW\CKernel\Kernel();
$Kernel->set_debug(true);

header('Content-Type: text/html; charset=utf-8'); //On indique un header en utf-8 de type html
setcookie("arecookiesenabled", "yes", time()+365*24*60*60*10); //anti-cache


//Inclusion fonction
$dir = opendir($path.'kernel/fonctions'); //Ouverture du dossier fonctions se trouvant à la racine
$dir_arr = array('.', '..', 'index.html', 'bbcode_parse.php', '.htaccess'); //Les fichiers & dossiers à ignorer à la lecture

while(false !== ($file = readdir($dir))) //Si on a un fichier
{
	//Si c'est un fichier, et que ce n'est pas une sauvegarde auto, on inclu.
	if(!in_array($file, $dir_arr) && !preg_match("#~$#", $file))
	{
		require_once($path.'kernel/fonctions/'.$file);
	}
}

closedir($dir); //Fermeture du dossier
unset($dir, $dir_arr, $file); //Suppression des variables
//Fin Inclusion fonction

//Fichier de config
require_once($path.'kernel/conf.php');
//Fichier de config

//Sql
if($bd_enabled)
{
	if(file_exists($path.'/modules/'.$bd_module.'/kernel_init.php'))
	{
		require_once($path.'/modules/'.$bd_module.'/kernel_init.php');
	}
}
//Sql

//Template
if(file_exists($path.'/modules/'.$tpl_module.'/kernel_init.php'))
{
	require_once($path.'/modules/'.$tpl_module.'/kernel_init.php');
}
//Template

//Serveur memcache (permet de stocker des infos direct sur la ram avec ou sans limite dans le temps)
$Memcache = new BFW\CKernel\Ram();
//Fin Serveur memcache

//Inclusions des modules
$Modules = new BFW\CKernel\Modules();

$dir = opendir($path.'modules');
$dir_arr = array('.', '..', '.htaccess');

while(false !== ($file = readdir($dir)))
{
	//Si le fichier existe, on inclus le fichier principal du mod
	if(file_exists($path.'modules/'.$file.'/'.$file.'.php'))
	{
		//echo 'Inclus module : '.$file.'<br/>';
		require_once($path.'modules/'.$file.'/'.$file.'.php');
	}
}
closedir($dir);
unset($dir, $dir_arr, $file);
//Inclusions des modules

//Infos sur la page en cours
$http = $host = $_SERVER["HTTP_HOST"]; //Host
$self = $_SERVER['PHP_SELF']; //adresse propre de la page
$page_on_serv = secure(dirname($_SERVER['PHP_SELF'])."/".basename($_SERVER['PHP_SELF']));

$argv = '';
$key_lang = null;

if(isset($_SERVER['argv']) && is_array($_SERVER['argv']))
{
	foreach($_SERVER['argv'] as $key => $val)
	{
		if(!($val == 'lang=fr' || $val == 'lang=en'))
		{
			if($key == 0 || $key_lang == 0)
			{
				$argv = '?';
			}
			else
			{
				$argv .= '&';
			}
			
			$argv .= $val;
		}
		else
		{
			$key_lang = $key;
		}
	}
}
//Infos sur la page en cours

//Visiteur
$Visiteur = new BFW\CKernel\Visiteur();
//Visiteur

//Chargement des modules
$time = 'after_visiteur';
if(array_key_exists($time, $Modules->mod_load))
{
	if(is_array($Modules->mod_load[$time]))
	{
		foreach($Modules->mod_load[$time] as $name)
		{
			require_once($path.'modules/'.$name.'/inclus.php');
		}
	}
}
//Chargement des modules

//Chemin
/**
 * @name path_kernel : Chemin vers le kernel
 */
define('path_kernel', $path.'kernel/');

/**
 * @name path_cache : Chemin vers la racine du dossier cache
 */
define('path_cache', $path.'cache/');

/**
 * @name path_controler : Chemin vers la racine du dossier controlers
 */
define('path_controler', $path.'controlers/');

/**
 * @name path_modeles : Chemin vers la racine du dossier modeles
 */
define('path_modeles', $path.'modeles/');

/**
 * @name path_modules : Chemin vers la racine du dossier modules
 */
define('path_modules', $path.'modules/');

/**
 * @name path_view : Chemin vers la racine du dossier view
 */
define('path_view', $path.'view/');

/**
 * @name path : Chemin vers la racine
 */
define('path', $path);
//Chemin

//Chargement des modules
$time = 'end_kernel';
if(array_key_exists($time, $Modules->mod_load))
{
	if(is_array($Modules->mod_load[$time]))
	{
		foreach($Modules->mod_load[$time] as $name)
		{
			require_once($path.'modules/'.$name.'/inclus.php');
		}
	}
}
//Chargement des modules
?>