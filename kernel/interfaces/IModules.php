<?php

namespace BFW\IKernel;

interface IModules
{
	/**
	 * Permet de déclarer un nouveau modules
	 * @param string $name : Le nom du modules
	 * @param string $time [opt] : Si indiqué, le module sera chargé à un moment précis du noyau, 
	 * 								sinon il sera chargé directement.
	 */	
	public function new_mods($name, $time=null);
	
	/**
	 * Permet de vérifier si un module existe
	 * @param string $name : Le nom du module
	 * @return bool : true s'il existe, false sinon
	 */
	public function isset_mods($name);
}
?>