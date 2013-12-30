<?php
$init = get(0);

$oldMDP = post('oldMDP');
$newMDP = post('newMDP');

$backColor = post('backColor');
$backOpacity = post('backOpacity');
$textColor = post('textColor');

if(!is_null($oldMDP) || !is_null($newMDP))
{
	$status = 403;
	$erreur = '';
	
	if(!empty($oldMDP) && !empty($newMDP))
	{
		$MUser = new \modules\users\modeles\Users;
		$mdpBDD = $MUser->getMdp($idUser);
		
		$mdpHash = hashage($oldMDP);
		
		if($mdpHash == $mdpBDD)
		{
			$newMDP = hashage($newMDP);
			
			if($MUser->setMdp($idUser, $newMDP)) {$status = 200;}
			else {$erreur = 'Erreur à la modification.';}
		}
		else {$erreur = 'Mauvais mot de passe.';}
	}
	else {$erreur = 'Les 2 champs doivent être rempli.';}
	
	echo json_encode(array('status' => $status, 'erreur' => $erreur));
}
elseif($init == 'init')
{
	$MUser = new \modules\users\modeles\Users;
	
	$BackColor = '#ffffff';
	$BackOpacity = 93;
	$TextColorBlack = true;
	
	if(!$MUser->setBackgroundColor($idUser, $BackColor)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_BackColor', $BackColor);}
	
	if(!$MUser->setBackgroundOpacity($idUser, $BackOpacity)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_BackOpacity', $BackOpacity);}
	
	if(!$MUser->setColorTextBlack($idUser, $TextColorBlack)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_TextColorBlack', $TextColorBlack);}
	
	echo json_encode(array(
		'backgroundColor' => $BackColor,
		'backgroundOpacity' => $BackOpacity,
		'textColor' => 'black',
		'textColorBlack' => $TextColorBlack
	));
}
elseif(!is_null($backColor) || !is_null($backOpacity) || !is_null($textColor))
{
	if(empty($backColor) || empty($backOpacity) || empty($textColor)) {ErrorView(500, false);}
	
	$MUser = new \modules\users\modeles\Users;
	
	if(!$MUser->setBackgroundColor($idUser, $backColor)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_BackColor', $backColor);}
	
	if(!$MUser->setBackgroundOpacity($idUser, (int) $backOpacity)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_BackOpacity', (int) $backOpacity);}
	
	$textColor = ($textColor == 'true') ? true : false;
	if(!$MUser->setColorTextBlack($idUser, $textColor)) {ErrorView(500, false);}
	else {$Memcache->setVal('U'.$idUser.'_TextColorBlack', (int) $textColor);}
}
else
{
	$TPL = new \BFW_Tpl\Classes\Template('monCompte.html');
	
	$edit = get(0);
	if(!is_null($edit) && $edit == 'ok') {$TPL->AddBlockWithEnd('modifOk');}
	
	$BackColor = $Memcache->getVal('U'.$idUser.'_BackColor');
	$BackOpacity = $Memcache->getVal('U'.$idUser.'_BackOpacity');
	$TextColorBlack = $Memcache->getVal('U'.$idUser.'_TextColorBlack');

	$textColorCheck = ($TextColorBlack == 1) ? 'checked="checked"' : '';
	$TPL->AddVars(array(
		'backColor' => $BackColor,
		'backOpacity' => $BackOpacity,
		'textColorCheck' => $textColorCheck
	));
	
	$TPL->End();
}
?>