<?php
$nom = post('nom');
$po = post('po');

if(!is_null($nom) && !is_null($po) && !empty($nom))
{
	if(empty($po)) {$po = 0;}
	$po = set_po($po);
	
	$MPerso = new \modeles\Perso();
	$MPerso->create($idUser, $nom, $po);
}
else {ErrorView(400);}
?>