<?php

$typeGraph = get(0, 'general');

if($typeGraph == 'onePerso')
{
	$idPerso = get(1);
	if(is_null($idPerso)) {exit;} //Sécurité
	
	$MPerso = new \modeles\Perso;
	$perso = $MPerso->getPerso($idPerso);
	$nomPerso = $perso['nom'];
	
	if($perso['idUser'] != $idUser) {exit;} //Sécurité : Peux pas voir le perso d'un autre
}

if($typeGraph == 'allPerso')
{
	$MPerso = new \modeles\Perso;
	$persos = $MPerso->getAll($idUser);
}

$ventes = array();
$dateDeb = new \BFW\CKernel\Date();
$dateDeb->DateTime->setTime(0, 0, 0);
$jourNow = $dateDeb->DateTime->format('N');

$sub = $jourNow-1;
$dateDeb->modify('-'.$sub.' days'); //Positionne au lundi de la semaine courante

$MPersoItem = new \modeles\PersoItem;

for($i=0; $i<=3; $i++)
{
	$dateFin = new \BFW\CKernel\Date($dateDeb->getSql());
	$dateFin->modify('+7 jours'); //Avance d'une semaine
	$dateFin->modify('-1 seconde'); //Jour d'avant à 23h59:59
	
	$ventes[$i]['date'] = $dateDeb->DateTime->format('d/m').' - '.$dateFin->DateTime->format('d/m');
	
	//Récupe les infos
	if($typeGraph == 'general') {$ventes[$i]['val'] = $MPersoItem->getPoVenteSemaineUser($idUser, $dateDeb, $dateFin);}
	if($typeGraph == 'allPerso') {$ventes[$i]['val'] = $MPersoItem->getPoVenteSemaineAllPerso($idUser, $dateDeb, $dateFin);}
	if($typeGraph == 'onePerso') {$ventes[$i]['val'] = $MPersoItem->getPoVenteSemainePerso($idPerso, $dateDeb, $dateFin);}
	
	$dateDeb->modify('-7 jours');
}

//inversion de $vente
krsort($ventes);

if($typeGraph == 'general') {$headGraph = '[["Semaine", "Vendu"]';}
if($typeGraph == 'onePerso') {$headGraph = '[["Semaine", "'.$nomPerso.'"]';}
if($typeGraph == 'allPerso')
{
	$headGraph = '[["Semaine"';
	foreach($persos as $perso) {$headGraph .= ',"'.$perso['nom'].'"';}
	$headGraph .= ']';
}

ob_clean();
echo $headGraph;

foreach($ventes as $vente)
{
	echo ',["'.$vente['date'].'"';
	
	if(is_array($vente['val']))
	{
		foreach($vente['val'] as $ventePerso)
		{
			if(is_null($ventePerso['po'])) {$ventePerso['po'] = 0;}
			echo ', '.$ventePerso['po'];
		}
	}
	else {echo ', '.$vente['val'];}
	
	echo ']';
}

echo ']';
