<?php
$nom = post('nom');
$po = post('po');

if(!is_null($nom) && !is_null($po) && !empty($nom))
{
	var_dump($po);
	
	$po = str_replace(',', '', $po);
	$po = str_replace('.', '', $po);
	$po = str_replace(' ', '', $po);
	$po = (int) $po;
	
	if(empty($po)) {$po = 0;}
	
	$MPerso = new \modeles\Perso();
	$MPerso->create($idUser, $nom, $po);
}
else {ErrorView(400);}
?>