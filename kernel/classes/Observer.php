<?php
/**
 * Classes en rapport avec les observers
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW\CKernel;

/**
 * Classe Observer
 * @package BFW
 */
class Observer implements \SplObserver
{
	/**
	 * Méthode par défaut appelé lorsque l'observer se déclanche
	 * 
	 * @param SplSubject $subject : Le sujet déclanchant l'observer
	 */
	public function update(SplSubject $subject)
	{
		
	}
	
	/**
	 * Méthode appelé lorsque l'observer se déclanche via la classe Kernel
	 * 
	 * @param SplSubject $subject : Le sujet déclanchant l'observer
	 * @param string     $action  : L'action à faire lors du déclanchement
	 */
	public function updateWithAction(SplSubject $subject, $action)
	{
		//Gestion de l'action.
		/*
		 * $action : 
		 * 	Maclass::MaMethode()
		 * 	Maclass->MaMethode()
		 * 	MaFonction()
		*/
		//Il faut gérer la porter des variables (classe/arguments)
		
		$class = null;
		$args = null;
		
		$match = array();
		$regex = '([0-9a-zA-Z.,_-]+)';
		$regex2 = '([0-9a-zA-Z.,_-]*)';
		
		if(strpos($action, '::') !== false)
		{
			$preg_match = preg_match('^'.$regex.'::'.$regex.'\('.$regex2.'\);$', $action, $match);
			$class = $match[1];
			$args = $match[3];
		}
		elseif(strpos($action, '->') !== false)
		{
			$preg_match = preg_match('^'.$regex.'::'.$regex.'\('.$regex2.'\);$', $action, $match);
			$class = $match[1];
			$args = $match[3];
		}
		else
		{
			$preg_match = preg_match('^'.$regex.'\('.$regex2.'\);$', $action, $match);
			$args = $match[2]; 
		}
		
		if(!is_null($class))
		{
			$class = str_replace('$', '', $class);
			global ${$class};
		}
			 
		if(!is_null($args))
		{
			$args = explode(',', $args);
			foreach($args as $arg)
			{
				$arg = str_replace('$', '', $arg);
				global ${$arg};
			}
		}
		
		try
		{
			eval($action);
		}
		catch(exception $e)
		{
			echo $e->getMessage();
		}
	}
}
?>