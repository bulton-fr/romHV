<?php

$ventes = array();
$dateDeb = new \BFW\CKernel\Date();
$dateDeb->DateTime->setTime(0, 0, 0);
$jourNow = $dateDeb->DateTime->format('N');

$sub = $jourNow-1;
$dateDeb->modify('-'.$sub.' days');

$MPersoItem = new \modeles\PersoItem;

for($i=0; $i<=3; $i++)
{
	$dateFin = new \BFW\CKernel\Date($dateDeb->getSql());
	$dateFin->modify('+7 jours'); //Avance d'une semaine
	$dateFin->modify('-1 seconde'); //Jour d'avant à 23h59:59
	
	$ventes[$i]['date'] = $dateDeb->DateTime->format('d/m').' - '.$dateFin->DateTime->format('d/m');
	
	//Récupe les infos
	$ventes[$i]['val'] = $MPersoItem->getPoVenteSemaine($idUser, $dateDeb, $dateFin);
	
	$dateDeb->modify('-7 jours');
}

//inversion de $vente
krsort($ventes);

ob_clean();
echo '[["Semaine", "Vendu"],["",0]';

foreach($ventes as $vente)
{
	echo ',["'.$vente['date'].'", '.$vente['val'].']';
}

echo ']';
