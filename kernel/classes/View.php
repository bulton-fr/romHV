<?php
/**
 * Classes géant les pages
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW\CKernel;

/**
 * Permet de gérer la vue et de savoir vers quel page envoyer
 * @package BFW
 */
class View extends Kernel implements \BFW\IKernel\IView
{
	/**
	 * @var $name_view : Le nom de la vue
	 */
	private $name_view;
	
	/**
	 * @var $link_file : L'arborescence interne dans les fichiers
	 */
	private $link_file = '';
	
	/**
	 * @var $arg : Les arguments get
	 */
	private $arg= array();
	
	/**
	 * @var $defaultPage : La page par défault (celle qui sert de page index au site)
	 */
	private $defaultPage;
	
	/**
	 * Constructeur
	 * @param string $default_page [opt] : La page par défaut du site (la page index du site)
	 */
	public function __construct($default_page=null)
	{
		$this->arg2get(); //Découpe pour obtenir les gets
		$this->verif_link();
		
		//Si la page par défaut a été indiqué, on la définie.
		if($default_page != null)
		{
			$this->set_defaultPage($default_page);
		}
	}
	
	/**
	 * Retourne le lien de la page
	 * @return string : Le lien de la page
	 */
	public function get_link()
	{
		if($this->link_file == '')
		{
			$this->decoupe_link();
		}
		
		return $this->link_file;
	}
	
	/**
	 * Récupère les informatios mises en get
	 */
	private function arg2get()
	{
		if($this->link_file == '')
		{
			$this->decoupe_link();
		}
		
		global $_GET;
		$get_id = 0;
		
		foreach($this->arg as $val)
		{
			$_GET[$get_id] = secure(trim($val));
			$get_id += 1;
		}
	}
	
	/**
	 * On vérifie le lien
	 */	
	private function verif_link()
	{
		if($this->link_file == '')
		{
			$this->decoupe_link();
		}
		
		global $_GET;
		
		//Si le fichier a ouvrir est indiqué dans la variable get 'page_uri' et s'il existe, on l'ouvre.
		if(isset($_GET['page_uri']))
		{
			global $path;
			$page_uri = secure(trim($_GET['page_uri']));
			
			if(file_exists($path.'controlers/'.$page_uri.'.php'))
			{
				$this->link_file= $page_uri;
			}
		}
	}
	
	/**
	 * Récupère le lien de la pge
	 */
	private function decoupe_link()
	{
		//Link de la forme : /compte/user/xx/yy avec le dossier compte, la page user et 2 valeurs get (xx et yy)
		
		global $_GET, $path, $base_url;
		$link = $_SERVER['REQUEST_URI']; //On récupère l'url courante
		
		$exBaseUrl = explode('/', $base_url);
		if(count($exBaseUrl) > 3)
		{
			unset($exBaseUrl[0], $exBaseUrl[1], $exBaseUrl[2]);
			$imBaseUrl = '/'.implode('/', $exBaseUrl);
			$lenBaseUrl = strlen($imBaseUrl);
			
			$link = substr($link, $lenBaseUrl);
		}
		
		//S'il s'agit de la page index ou racine, on envoi vers la page par défault
		if($link == '/index.php' || $link == '/')
		{
			$this->link_file = $this->defaultPage;
		}
		else
		{
			$link = substr($link, 1); //enlève le premier / de l'url
			$ex = explode('/', $link); //Découpage de l'url, on découpe sur chaque /
			
			$file_find = false; //Indique si le fichier a été trouvé
			$dir_find = false; //Indique si le dossier a été trouvé
			
			foreach($ex as $val)
			{
				if(!$file_find) //Tant qu'on a pas trouvé le fichier
				{
					//On rajoute un / à la fin du lien si on a commencé à mettre des choses dessus
					if($this->link_file != '')
					{
						$this->link_file .= '/';
					}
					
					$this->link_file .= $val; //Et on y rajoute la valeur lu
					
					//Si le fichier existe dans le dossier modèle. On passe la $file_find à true
					if(file_exists($path.'controlers/'.$this->link_file.'.php'))
					{
						$file_find = true;
					}
					
					//Si un dossier existe pourtant le nom, on passe $dir_find à true
					if(file_exists($path.'controlers/'.$this->link_file))
					{
						$dir_find = true;
					}
				}
				//Si le fichier a été trouvé, alors on ajoute la valeur lu aux argumets get.
				else
				{
					$this->arg[] = $val;
				}
			}
			
			//Si rien a été trouvé, on rajoute "/index" à la fin du lien
			if($file_find == false && $dir_find == true)
			{
				$this->link_file .= '/index';
			}
		}
	}
	
	/**
	 * Modifie la page par défault
	 * @param string $name : Le nom de la page index du site
	 */
	public function set_defaultPage($name)
	{
		$this->defaultPage = $name;
	}
}