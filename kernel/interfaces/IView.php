<?php

namespace BFW\IKernel;

interface IView
{
	/**
	 * Constructeur
	 * @param string $default_page [opt] : La page par défaut du site (la page index du site)
	 */
	public function __construct($default_page=null);
	
	/**
	 * Retourne le lien de la page
	 * @return string : Le lien de la page
	 */
	public function get_link();
	
	/**
	 * Modifie la page par défault
	 * @param string $name : Le nom de la page index du site
	 */
	public function set_defaultPage($name);
}
?>