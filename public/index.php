<?php
/**
 * Page index. Toutes les requêtes arrive ici.
 * @author Vermeulen Maxime
 * @package BFW
 */

require_once('../kernel/conf.php');

/** Gestion à faire à cause de l'url rewriting qui prend en compte tous les fichiers, css/js/images y compris. **/
$request_get = $request = $_SERVER['REQUEST_URI'];

$exBaseUrl = explode('/', $base_url);
if(count($exBaseUrl) > 3)
{
	unset($exBaseUrl[0], $exBaseUrl[1], $exBaseUrl[2]);
	
	$imBaseUrl = '/'.implode('/', $exBaseUrl);
	$lenBaseUrl = strlen($imBaseUrl);
	
	$request_get = $request = substr($request, $lenBaseUrl);
}

$ex_request_arg = explode('?', $request); //On découpe avant le ? qui correspond à un get
$ex_request = explode('.', $ex_request_arg[0]); //Pour la partie avant le ?, on découpe dès qu'on trouve un .
$file = $UrlAsk = $ex_request_arg[0];

if(count($ex_request) > 1 && ($request != '/index.php' || $request != '/'))
{
	//La personne a appelé une page
	//Il y a un . dans l'url
	//Il ne s'agit pas de la page index
	
	//Donc s'il y a un . ça peut être un fichier donc il faut vérifier s'il existe
	//Et s'il existe pas, on vérifie si c'est un controlleur ou pas
	//Si spa le cas, 404
	
	//Si c'est une extension, ça peut être à 2 endroits possibles.
	//Soit dans view, soit dans un module
	
	$findMod = substr($ex_request[0], 0, 9);
	
	$cntRequest = count($ex_request) -1;
	$ext = $ex_request[$cntRequest];
	
	$ErrorView = 200;
	$noExit = false;
	
	if($ext != 'php')
	{
		if(file_exists('../view/'.$file))
		{
			$path_request = 'view/';
		}
		elseif($findMod == '/modules/')
		{
			$dirRequest = explode('/', $ex_request[0]);
			$name_mods = $dirRequest[2];
			
			if(file_exists('../modules/'.$name_mods.'/externe.php'))
			{
				$path_request = '';
			}
			else
			{
				$ErrorView = 403;
			}
		}
		else
		{
			//On cherche si c'est un controleur ou pas
			$exUrl = explode('/', $file);
			$pathCtl = '';
			$find = false;
			
			foreach($exUrl as $val)
			{
				if($pathCtl != '')
				{
					$pathCtl .= '/';
				}
				$pathCtl .= $val;
				
				if(file_exists('../controlers/'.$pathCtl.'.php'))
				{
					$find = true;
					break;
				}
			}
			
			if($find == false)
			{
				$ErrorView = 404;
			}
			else
			{
				$noExit = true;
			}
		}
		
		if($ErrorView == 200)
		{
			if($ex_request[$cntRequest] == 'css')
			{
				header('Content-type: text/css');
			}
			elseif($ex_request[$cntRequest] == 'js')
			{
				header('Content-type: text/javascript');
			}
			else
			{
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				header('Content-type: '.finfo_file($finfo, '../'.$path_request.$file));
			}
			
			echo file_get_contents('../'.$path_request.$file);
		}
	}
	else
	{
		if($findMod == '/modules/')
		{
			$dirRequest = explode('/', $ex_request[0]);
			$name_mods = $dirRequest[2];
			
			if(file_exists('../modules/'.$name_mods.'/externe.php'))
			{
				$path_request = '';
				$request = $ex_request_arg[0];
				
				require_once('../kernel/kernel.php');
				require_once('../modules/'.$name_mods.'/externe.php');
				
				if(in_array($ex_dir_request[3], $lst_require_ok))
				{
					require_once('../'.$path_request.$request);
				} 
			}
			else {$ErrorView = 403;}
		}
		else {$ErrorView = 404;}
	}
	
	if($ErrorView != 200)
	{
		require_once('../kernel/kernel.php');
		ErrorView($ErrorView);
	}
	
	if($noExit == false)
	{
		exit;
	}
}

/** Gestion à faire à cause de l'url rewriting qui prend en compte tous les fichiers, css/js/images y compris. **/
require_once('../kernel/kernel.php');

$page_title = '';
$View = new BFW\CKernel\View();
$View->set_defaultPage($DefaultControler);

//La page
if(file_exists('../cache/'.$View->get_link().'.phtml'))
{
	require_once('../cache/'.$View->get_link().'.phtml');
}
elseif(file_exists('../controlers/'.$View->get_link().'.php'))
{
	require_once('../controlers/'.$View->get_link().'.php');
}
else {ErrorView(404);}

?>