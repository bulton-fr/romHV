<?php
$TPL = new \BFW_Tpl\Classes\Template('perso/vente.html');

$MPerso = new \modeles\Perso;

$idPerso = post('idPerso');
if(is_null($idPerso)) {echo 'Désolé j\'ai crashé.';}

$verif = $MPerso->persoBeUser($idPerso, $idUser);
if($verif == false) {echo 'Désolé j\'ai crashé.';}



$suite = post('suite', 0); //Le nombre de fois qu'on a fait afficher la suite.
$nbParPage = 30;
$start = $suite*$nbParPage;

$limit = array(
	'start' => $start,
	'nb' => $nbParPage
);

$triRow = substr(post('triRow', 'venteDate'), 5);
$triSens = post('triSens');

$RowAccept = array('Item', 'Perso', 'Date', 'Enchere', 'Rachat');
$SensAccept = array('ASC', 'DESC');

if(!in_array($triRow, $RowAccept)) {$triRow = 'Date';}
if(!in_array($triSens, $SensAccept)) {$triSens = null;}

if(is_null($triSens))
{
	if($triRow == 'Date') {$triSens = 'DESC';}
	else {$triSens = 'ASC';}
}

$MPersoItem = new \modeles\PersoItem();
$items = $MPersoItem->getVentePerso($idPerso, $limit, array($triRow, $triSens));

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

$nbVente = $MPersoItem->getNbVentePerso($idPerso);
if($nbVente > ($start+$nbParPage))
{
	if($start == 0) {$TPL->AddBlockWithEnd('suite');}
	else {$TPL->AddBlockWithEnd('suiteAfter');}
}

$MPersoItemStat = new \modeles\PersoItemStat;

foreach($items as $item)
{
	$item['enchere'] = get_po($item['enchere']);
	$item['rachat'] = get_po($item['rachat']);
	
	$dateVendu = new \BFW\CKernel\Date($item['dateDebut']);
	$dateVendu->modify('+'.$item['duree'].' jours');
	$item['date'] = $dateVendu->aff_simple();
	$item['color'] = get_color_item($item['color']);
	
	$TPL->AddBlock('items', $item);
	
	$moreInfos = false;
	$stats = $MPersoItemStat->getStats($item['ref']);
	
	if(!empty($item['notes']) || count($stats) > 0) {$moreInfos = true;}
	
	if($moreInfos)
	{
		$moreInfos = '';
		if(count($stats) > 0)
		{
			foreach($stats as $stat) {$moreInfos .= $stat['nom']."\n";}
		}
		
		if(!empty($item['notes']) && $moreInfos != '') {$moreInfos .= "\n";}
		$moreInfos .= $item['notes'];
		
		$TPL->AddBlockWithEnd('notes', array('notes' => nl2br($moreInfos)));
	}
}
$TPL->EndBlock();

$TPL->End();
?>