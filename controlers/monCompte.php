<?php
$oldMDP = post('oldMDP');
$newMDP = post('newMDP');

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
else
{
	$TPL = new \BFW_Tpl\Classes\Template('monCompte.html');
	
	$edit = get(0);
	if(!is_null($edit) && $edit == 'ok') {$TPL->AddBlockWithEnd('modifOk');}
	
	$TPL->End();
}
?>