<?php
$idPerso = post('idPerso');
$nom = post('nom');
$po = post('po');

if(!is_null($idPerso) && (!is_null($nom) || !is_null($po)))
{
	$idPerso = (int) $idPerso;
	$MPerso = new \modeles\Perso();
	
	if(!is_null($nom) && !empty($nom))
	{
		if($MPerso->setNom($idPerso, $nom)) {echo $nom;}
		else {ErrorView(500);}
	}
	elseif(!is_null($po))
	{
		if(empty($po)) {$po = 0;}
		
		$po = set_po($po);
		
		if($MPerso->setPo($idPerso, $po)) {echo get_po($po);}
		else {ErrorView(500);}
	}
	else {ErrorView(400);}
}
else {ErrorView(400);}
?>