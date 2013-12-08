<?php
/**
 * Fonctions en rapport avec les po
 * 
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */

/**
 * Affiche correctement les po
 * 
 * @param int $po : Les po
 * 
 * @return string
 */
function get_po($po)
{
	if(!is_float($po) && !settype($po, 'float'))
	{
		global $Kernel;
		if($Kernel->get_debug()) {throw new Exception('Erreur dans la transformation en type float.');}
		else {return '0';}
	}
	
	return number_format($po, 0, '', '.');
}


/**
 * Mise en forme des po pour la bdd (en autre)
 * 
 * @param string $po : Les po
 * 
 * @return int
 */
function set_po($po)
{
	if(!is_string($po) && !settype($po, 'string'))
	{
		global $Kernel;
		if($Kernel->get_debug()) {throw new Exception('Erreur dans la transformation en type string.');}
		else {return 0;}
	}
	
	$po = str_replace(',', '', $po);
	$po = str_replace('.', '', $po);
	$po = str_replace(' ', '', $po);
	
	return (int) $po;
}
