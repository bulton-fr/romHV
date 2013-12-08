<?php
$TPL = new \BFW_Tpl\Classes\Template('ventes/histo.html');

$dateDeb = new \BFW\CKernel\Date();
$dateDeb->DateTime->setTime(0, 0, 0);
$jourNow = $dateDeb->DateTime->format('N');

$sub = $jourNow-1;
$dateDeb->modify('-'.$sub.' days'); //Positionne au lundi de la semaine courante

$dateFin = new \BFW\CKernel\Date($dateDeb->getSql());
$dateFin->modify('+7 jours'); //Avance d'une semaine
$dateFin->modify('-1 seconde'); //Jour d'avant à 23h59:59

$suite = post('suite', 0); //Le nombre de fois qu'on a fait afficher la suite.
$nbParPage = 30;
$start = $suite*$nbParPage;

$limit = array(
	'start' => $start,
	'nb' => $nbParPage
);

$triRow = substr(post('triRow', 'venteDate'), 5);
$triSens = post('triSens');

$RowAccept = array('Item', 'Perso', 'Date', 'Achat', 'Po');
$SensAccept = array('ASC', 'DESC');

if(!in_array($triRow, $RowAccept)) {$triRow = 'Date';}
if(!in_array($triSens, $SensAccept)) {$triSens = null;}

if(is_null($triSens))
{
	if($triRow == 'Date') {$triSens = 'DESC';}
	else {$triSens = 'ASC';}
}

$MPersoItem = new \modeles\PersoItem();
$items = $MPersoItem->getVentesAllPerso($idUser, $limit, array($triRow, $triSens));

if($suite == 0)
{
	$varTri = array(
		'triItem' => ($triRow == 'Item') ? ' tri' : '',
		'triPerso' => ($triRow == 'Perso') ? ' tri' : '',
		'triDate' => ($triRow == 'Date') ? ' tri' : '',
		'triAchat' => ($triRow == 'Achat') ? ' tri' : '',
		'triPo' => ($triRow == 'Po') ? ' tri' : ''
	);
	
	if($triSens == 'DESC')
	{
		 foreach($varTri as $key => $val)
		 {
		 	if(!empty($val))
		 	{
		 		$varTri[$key] .= 'Inverse';
				break;
			}
		 }
	}
	
	$TPL->AddBlockWithEnd('start', $varTri);
}

$nbVente = $MPersoItem->getNbVenteAllPerso($idUser);
if($nbVente > ($start+$nbParPage)) {$TPL->AddBlockWithEnd('suite');}

foreach($items as $item)
{
	$item['poGagne'] = get_po($item['poGagne']);
	$dateVendu = new \BFW\CKernel\Date($item['dateVendu']);
	$item['dateVendu'] = $dateVendu->aff_simple();
	
	$TPL->AddBlockWithEnd('items', $item);
}

$TPL->End();
?>