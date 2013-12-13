<?php
/**
 * Classe en rapport avec Memcache
 * 
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW\CKernel;

/**
 * Gestion du serveur avec memcache
 * @package BFW
 */
class Ram extends Kernel implements \BFW\IKernel\IRam
{
	/**
	 * @var $ext_load : Permet de savoir si l'extension est chargé ou pas
	 */
	private $ext_load = false;
	
	/**
	 * @var $Server : Le serveur
	 */
	private $Server;
	
	/**
	 * @var $no_ext : Permet de savoir si l'extension existe ou pas
	 */
	private $no_ext = false;
	
	/**
	 * @var $debug : Permet d'activer ou non le mode débug
	 */
	public $debug = false;
	
	/**
	 * @var $lastVal : Stock le contenu de la dernière valeur récupéré
	 */
	private $lastVal;
	
	/**
	 * @var $lastKey : Stock le contenu de la dernière clé récupéré
	 */
	private $lastKey;
	
	/**
	 * Constructeur
	 * Se connecte au serveur memcache indiqué, par défaut au localhost
	 * 
	 * @param string [optionel] le nom du serveur memcache
	 * @return bool [optionel] : Renvoi false si l'extension memcached n'est pas installé
	 */
	public function __construct($name='localhost')
	{
		if(extension_loaded('memcache'))
		{
			//memcache_debug(true);
			$this->ext_load = true;
		
			$this->Server = new \Memcache;
			$co = $this->Server->connect($name);
			
			//if(!$co) {echo 'Echec de la connexion !!'; echo '<br/><br/>';}
			//else {echo 'Var dump : ';var_dump($this->Server);echo '<br/><br/>';}
		}
		//else {echo 'No extension !!'; echo '<br/><br/>';return false;}
		else
		{
			$this->no_ext = true; return false;
		}
	}
	
	/**
	 * Permet de retourner la valeur d'une clé. Si la clé n'existe pas, on la met en mémoire
	 * @param string : Clé correspondant à la valeur
	 * @param mixed : Les nouvelles données
	 * @param int [optionnel] Le temps en seconde avant expiration. 0 illimité, max 30jours
	 * @return mixed La valeur demandée
	 */
	public function val($key, $data, $expire=0)
	{
		if($this->no_ext == false) //Récupère la valeur
		{
			$ret = $this->Server->get($key);
		}
		else
		{
			$ret = false;
		}
		
		if($ret == false) //Si elle n'est pas en ram, on va la chercher, on la récupère, la met en ram et la renvoi
		{
			//On ajoute au serveur memcache les infos retourné par sql
			if($this->no_ext == false)
			{
				$this->Server->set($key, $data, 0, $expire);
			}
			else //Gestion s'il l'extension n'existe pas -> Stockage sur fichier.
			{
				global $path;
				
				if(file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'))
				{
					$json = json_decode(file_get_contents($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'));
					$expire = $json->expire;
					$create = $json->create;
					
					if($expire != 0)
					{
						$calcul = $create+$expire;
						$now = time();
						if($calcul >= $now)
						{
							$data = $json->data;
						}
					}
					else
					{
						$data = $json->data;
					}
				}
				else
				{
					$stock = array('expire' => $expire, 'create' => time(), 'data' => $data);
					$fop = fopen($path.'kernel/Memcache_ifnoExt/'.$key.'.txt', 'w+');
					fputs($fop, json_encode($stock));
					fclose($fop);
				}
			}
		}
		
		$this->maj_lasts($key, $data);
		return $data;
	}
	
	/**
	 * On modifie le temps avant expiration des infos sur le serveur memcached pour une clé choisie.
	 * @param string la clé disignant les infos concerné
	 * @param int le nouveau temps avant expiration (0: pas d'expiration, max 30jours)
	 */
	public function maj_expire($key, $exp)
	{
		if($this->no_ext == false)
		{
			$ret = $this->Server->get($key); //Récupère la valeur
			
			//On la "modifie" en remettant la même valeur mais en changeant le temps
			//avant expiration si une valeur a été retournée
			if($ret != false)
			{
				$this->Server->replace($key, $ret, 0, $exp);
			}
		}
		else
		{
			global $path;
			if(file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'))
			{
				$data = json_decode(file_get_contents($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'));
				$data->expire = $exp;
				$data->create = time();
				
				$fop = fopen($path.'kernel/Memcache_ifnoExt/'.$key.'.txt', 'w+');
				fputs($fop, json_encode($data));
				fclose($fop);
			}
		}
	}
	
	/**
	 * Modifie les données pour une clé
	 * Toutes les anciennes données seront écrasé par les nouvelles.
	 * @param string : La clé
	 * @param mixed : Les nouvelles données
	 * @param int [optionnel] Le temps en seconde avant expiration. 0 illimité, max 30jours
	 */
	public function maj_data($key, $data, $expire=0)
	{
		if($this->no_ext == false)
		{
			$ret = $this->Server->get($key);
			
			if($ret != false)
			{
				$this->Server->replace($key, $data, 0, $expire);
			}
			else
			{
				$this->Server->set($key, $data, 0, $expire);
			}
			
			$this->maj_lasts($key, $data);
		}
		else
		{
			global $path;
			if(file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'))
			{
				$stock = array('expire' => $expire, 'create' => time(), 'data' => $data);
				$fop = fopen($path.'kernel/Memcache_ifnoExt/'.$key.'.txt', 'w+');
				fputs($fop, json_encode($stock));
				fclose($fop);
				
				$this->maj_lasts($key, $data);
			}
		}
	}
	
	/**
	 * Permet de savoir si la clé existe
	 * @param string la clé disignant les infos concernées
	 * @return bool True si la clé existe, False sinon
	 */
	public function if_val_exists($key)
	{
		if($this->no_ext == false)
		{
			$ret = $this->Server->get($key); //Récupère la valeur
			$this->maj_lasts($key, $ret);
			
			if($ret == false)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			global $path;
			return file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt');
		}
	}
	
	/**
	 * Supprime une clé
	 * @param string la clé disignant les infos concernées
	 * @return bool True si la suppression à réussi, False sinon
	 */
	public function delete($key)
	{
		if($this->no_ext == false)
		{
			if($this->Server->delete($key))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			global $path;
			
			if(file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'))
			{
				unlink($path.'kernel/Memcache_ifnoExt/'.$key.'.txt');
			}
			return true;
		}
	}
	
	/**
	 * Accesseur vers l'attribut $lastVal
	 * @return string : La valeur de l'attribut
	 */
	public function get_lastVal()
	{
		return $this->lastVal;
	}
	
	/**
	 * Accesseur vers l'attribut $lastKey
	 * @return string : La valeur de l'attribut
	 */
	public function get_lastKey()
	{
		return $this->lastKey;
	}
	
	/**
	 * Met à jour les attributs lastVal et lastKey
	 * @param string $key : La dernière clé
	 * @param string $data : La dernière val
	 */
	public function maj_lasts($key, $data)
	{
		$this->lastKey = $key;
		$this->lastVal = $data;
	}
	
	
	
	/**
	 * Permet de retourner la valeur d'une clé. Si la clé n'existe pas, on la met en mémoire
	 * @param string : Clé correspondant à la valeur
	 * @param mixed : Les nouvelles données
	 * @param int [optionnel] Le temps en seconde avant expiration. 0 illimité, max 30jours
	 * @return mixed La valeur demandée
	 */
	public function getVal($key)
	{
		$data = null;
		
		if($this->no_ext == false) //Récupère la valeur
		{
			$data = $this->Server->get($key);
		}
		else
		{
			global $path;
				
			if(file_exists($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'))
			{
				$json = json_decode(file_get_contents($path.'kernel/Memcache_ifnoExt/'.$key.'.txt'));
				
				$expire = $json->expire;
				$create = $json->create;
				
				if($expire != 0)
				{
					$calcul = $create+$expire;
					$now = time();
					if($calcul >= $now)
					{
						$data = $json->data;
					}
				}
				else
				{
					$data = $json->data;
				}
			}
		}
		
		return $data;
	}
}