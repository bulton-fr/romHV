<?php
$TPL = new \BFW_Tpl\Classes\Template('ventes/me.html');

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
$items = $MPersoItem->getVenteAllPerso($idUser, $limit, array($triRow, $triSens));

if($suite == 0)
{
	$varTri = array(
		'triItem' => ($triRow == 'Item') ? ' tri' : '',
		'triPerso' => ($triRow == 'Perso') ? ' tri' : '',
		'triDate' => ($triRow == 'Date') ? ' tri' : '',
		'triEnchere' => ($triRow == 'Enchere') ? ' tri' : '',
		'triRachat' => ($triRow == 'Rachat') ? ' tri' : ''
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
	$item['enchere'] = get_po($item['enchere']);
	$item['rachat'] = get_po($item['rachat']);
	
	$dateVendu = new \BFW\CKernel\Date($item['dateDebut']);
	$dateVendu->modify('+'.$item['duree'].' jours');
	$item['date'] = $dateVendu->aff_simple();
	
	$TPL->AddBlockWithEnd('items', $item);
}

$TPL->End();
?>