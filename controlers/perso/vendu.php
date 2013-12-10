<?php
$TPL = new \BFW_Tpl\Classes\Template('perso/vendu.html');

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
$items = $MPersoItem->getVenduPerso($idPerso, $limit, array($triRow, $triSens));

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

$nbVente = $MPersoItem->getNbVenduPerso($idPerso);
if($nbVente > ($start+$nbParPage))
{
	if($start == 0) {$TPL->AddBlockWithEnd('suite');}
	else {$TPL->AddBlockWithEnd('suiteAfter');}
}

$MPersoItemStat = new \modeles\PersoItemStat;

foreach($items as $item)
{
	$item['poGagne'] = get_po($item['poGagne']);
	
	$dateVendu = new \BFW\CKernel\Date($item['dateVendu']);
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