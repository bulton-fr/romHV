<?php
/**
 * Fonctions en rapport avec le gestionaire d'hv
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

/**
 * Renvoi les bonnes couleurs (changement de couleur à cause du design)
 * 
 * @param string $color : La couleur à mettre normalement
 * 
 * @return string : La couleur à utiliser
 */
function get_color_item($color)
{
	$color = str_replace('ffffff', '000000', $color); //Blanc en noir
	$color = str_replace('00ff00', '00C000', $color); //Vert plus foncé car trop clair
	
	return $color;
}

/**
 * Date française vers anglaise (utlise pour la classe Date)
 * 
 * @param string $date : La date au format française
 * 
 * @return string
 */
function dateFr2Us($date)
{
	$exDateTime = explode(' ', $date);
	$exDate = explode('/', $exDateTime[0]);
	
	if(count($exDate) == 1)
	{
		$exDate = explode('-', $exDateTime[0]);
		if(count($exDate) == 1) {return $date;}
	}
	
	$jour = $mois = $annee = '';
	foreach($exDate as $i => $val)
	{
			if($i == 1) {$mois = $val;}
		elseif(strlen($val) == 4) {$annee = $val;}
		else {$jour = $val;}
	}
	
	$dateUs = $annee.'-'.$mois.'-'.$jour;
	if(isset($exDateTime[1])) {$dateUs .= ' '.$exDateTime[1];}
	
	return $dateUs;
}
