<?php
$ref = post('ref', '');
$typeAchat = post('type', 'rachat');
$po = set_po(post('po'));


if(empty($ref)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;
$persoItem = $MPersoItem->getPersoItem($ref);

if($idUser == $persoItem['idUser'])
{
    if(!$MPersoItem->setVendu($ref, $typeAchat, $po)) {ErrorView(500);}
}
else {ErrorView(500);}
?>