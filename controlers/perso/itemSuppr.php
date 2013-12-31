<?php

$ref = post('ref', '');
if(empty($ref)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;

$item = $MPersoItem->getPersoItem($ref);
if(count($item) == 0) {ErrorView(500);}

$idUserBdd = (int) $item['idUser'];

if($idUserBdd == $idUser)
{
	if(!$MPersoItem->supprItem($ref)) {ErrorView(500);}
}
else {ErrorView(403);}
?>