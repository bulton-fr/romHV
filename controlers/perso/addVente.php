<?php
$token = post('token');
$Form = new \BFW\CKernel\Form('FormAddItem');

if($Form->verif_token())
{
	$idPerso = post('idPerso');
	$idItem = post('idItem');
	$idStat[] = post('idStat1');
	$idStat[] = post('idStat2');
	$idStat[] = post('idStat3');
	$idStat[] = post('idStat4');
	$idStat[] = post('idStat5');
	$idStat[] = post('idStat6');
	$enchere = post('enchere');
	$rachat = post('rachat');
	$date = dateFr2Us(post('date'));
	$duree = (int) post('duree');
	$notes = post('notes');
	
	if(is_null($idPerso) || is_null($idItem) || is_null($enchere) || is_null($rachat)) {ErrorView(400);}
	//foreach($idStat as $stat) {if(is_null($stat)) {ErrorView(400);}} //<-- Champ non obligatoire
	
	if(is_null($date) || is_null($duree) || empty($date))
	{
		$date = '';
		$duree = 0;
	}
	else
	{
		$datePost = new \BFW\CKernel\Date($date);
		$date = $datePost->getSql();
	}
	
	$enchere = set_po($enchere);
	$rachat = set_po($rachat);
	
	$MPersoItem = new \modeles\PersoItem;
	$result = $MPersoItem->add($idUser, $idPerso, $idItem, $idStat, $enchere, $rachat, $date, $duree, $notes);
	
	if($result) {echo 'OK';}
	else {ErrorView(500);}
}
else {ErrorView(409);}
?>