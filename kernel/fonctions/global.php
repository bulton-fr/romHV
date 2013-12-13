<?php
/**
 * Toutes les fonctions de base utilisé un peu partout dans les scripts
 * @author Vermeulen Maxime
 * @package BFW
 */

/**
 * Permet d'hasher un mot de passe
 * @param string le mot de passe en clair
 * @return string le mot de passe hashé
 */
function hashage($val)
{
	return substr(hash('sha256', md5($val)), 0, 32);
}

/**
 * Permet de sécuriser une variable
 * @param mixed la variable à sécuriser (les types string, nombre et array sont géré)
 * @param bool mettre à true pour que la variable ne subisse pas un htmlentities()
 * @param bool mettre à true si on agit sur le nom d'une variable car par exemple "cou_cou" devient "cou\_cou"
 */
function secure($string, $html=false, $null_cslashe=false)
{
	/*
	A propos de $null_cslashes ; 
		A désactivé si on le fait sur le nom de variable, car sur un nom comme coucou ça passe, sur cou_cou, ça devient cou\_cou ^^
		(peut être génant sur un nom de variable dans le cas par exemple de $_POST['coucou'] ou $_POST['cou_cou'] pour l'exemple au-dessus)
	*/
	
	if(is_array($string)) //Au cas où la valeur à vérifier soit un array (peut arriver avec les POST)
	{
		foreach($string as $key => $val)
		{
			unset($string[$key]); #Dans le cas où après si $key est modifié, alors la valeur pour la clé non sécurisé existerais toujours et la sécurisation ne servirais à rien.
			$key = secure($key, true);
			$val = secure($val);
			$string[$key] = $val;
		}
	}
	else
	{
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		else // Pour tous les autres types
		{
			global $DB;
			
			$optHtmlentities = ENT_COMPAT;
			//commenté car problème de notice si php < 5.4
			//if(defined(ENT_HTML401)) {$optHtmlentities .= ' | '.ENT_HTML401;} //à partir de php5.4
			
			if($html == false)
			{
				$string = htmlentities($string, $optHtmlentities, 'UTF-8');
			}

			if($DB != null)
			{
				$string = $DB->protect($string);
			}
			
			if($null_cslashe == false)
			{
				$string = addcslashes($string, '%_');
			}
		}
	}
	
	return $string;
}

/**
 * Fonction de création de cookie
 * @param string $name : le nom du cookie
 * @param string $val : la valeur du cookie
 */
function create_cookie($name, $val)
{
	$two_weeks = time()+2*7*24*3600; //Durée d'existance du cookie
	@setcookie($name, $val, $two_weeks);
}

/**
 * Fonction nl2br refait. Celle de php AJOUTE <br/> APRES les \n, il ne les remplace pas.
 * @param string $str : le texte à convertir
 * @return string le texte converti
 */
function nl2br_replace($str)
{
	return $newstr = str_replace("\n", '<br>', $str);
}

/**
 * Permet de rediriger une page
 * @param string $page : la page vers laquelle rediriger
 */
function redirection($page)
{
	header('Location: '.$page);
	exit;
}

/**
 * Sécurise la valeur du post demandé et la renvoie
 * @param string $key : La donnée post demandée
 * @param mixed $default [opt] : La valeur par défault qui sera retourné si le get existe pas. Null si pas indiqué
 * @param bool $html : Savoir si on applique l'htmlentities (false pour oui, true pour non)
 * @return string : La valeur demandé sécurisé
 */
function post($key, $default=null, $html=false)
{
	if(isset($_POST[$key]))
	{
		$post = $_POST[$key];
		
		if(is_string($post))
		{
			$post = trim($post);
		}
		
		return secure($post, $html);
	}
	else
	{
		return null;
	}
}

/**
 * Sécurise la valeur du get demandé et la renvoie
 * @param string $key : La donnée get demandée
 * @param mixed $default [opt] : La valeur par défault qui sera retourné si le get existe pas. Null si pas indiqué
 * @return string : La valeur demandé sécurisé
 */
function get($key, $default=null)
{
	if(isset($_GET[$key]))
	{
		return secure(trim($_GET[$key]));
	}
	else
	{
		return $default;
	}
}

/**
 * Permet de savoir si le mail passé en paramètre est un e-mail valide ou non
 * @param string $mail : L'adresse e-mail à vérifier
 * @return bool : 
 */
function valid_mail($mail)
{
	return preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i', $mail);
}

/**
 * Affiche une page d'erreur
 * 
 * @param int/string $num : Le n° d'erreur à afficher ou l'erreur au format texte
 * @param bool			  : Indique si le cache du tampon de sortie doit être vidé ou pas
 */
function ErrorView($num, $cleanCache=true)
{
	if($cleanCache) {
		ob_clean(); //On efface tout ce qui a pu être mis dans le buffer pour l'affichage
	}
	
	global $request;
	global $Overlay, $Overlay_type, $Overlay_msg, $Overlay_opt;
	global $mini_infos, $mini_infos_msg;
	
	global $path;
	
	if(file_exists($path.'controlers/erreurs/'.$num.'.php'))
	{
		require_once($path.'controlers/erreurs/'.$num.'.php');
	}
	else
	{
		if(function_exists('http_response_code')) {http_response_code($num);}
		else {header(':', true, $num);}
		
		echo 'Erreur '.$num;
	}
	
	exit;
}

/**
 * Permet de logger une information. En temps normal il s'agit d'écrire ligne par ligne.
 * Si le fichier indiqué n'existe pas, il est créé, sinon c'est ajouté à la fin du fichier.
 * 
 * @param string  $file : Le lien vers le fichier
 * @param string  $txt  : La ligne de texte à écrire
 * @param boolean $date : Si à true, la date est ajouté au début de la ligne. Si false elle n'est pas mise.
 */
function logfile($file, $txt, $date=true)
{
	if($date == true)
	{
		$date = new \BFW\CKernel\Date();
		$dateTxt = $date->jour.'-'.$date->mois.'-'.$date->annee.' '.$date->heure.':'.$date->minute.':'.$date->seconde;
		$txt = '['.$dateTxt.'] '.$txt;
	}
	
	try {file_put_contents($file, rtrim($txt)."\n", FILE_APPEND);}
	catch(Exception $e) {echo '<br/>Impossible d\'écrire dans le fichier : '.$file.'<br/>';}
}