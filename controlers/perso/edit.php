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
	elseif(!is_null($po) && !empty($po))
	{
		$po = str_replace(',', '', $po);
		$po = str_replace('.', '', $po);
		$po = (int) $po;
		
		if($MPerso->setPo($idPerso, $po)) {echo $po;}
		else {ErrorView(500);}
	}
	else {ErrorView(400);}
}
else {ErrorView(400);}
?>