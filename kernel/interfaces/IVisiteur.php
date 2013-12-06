<?php

namespace BFW\IKernel;

interface IVisiteur
{
	/**
	 * Accesseur get vers les attributs
	 * @param string $name : Le nom de l'attribut
	 * @return mixed : La valeur de l'attribut
	 */
	public function __get($name);
	
	/**
	 * Accesseur set vers les attributs
	 * @param string $name : Le nom de l'attribut
	 * @param mixed $val : La nouvelle valeure de l'attribut
	 */
	public function __set($name, $val);
	
	/**
	 * Constructeur
	 * Récupère les infos et instancie la session
	 */
	public function __construct();
}
?>