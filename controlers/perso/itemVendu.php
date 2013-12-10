<?php
$ref = post('ref', '');
$typeAchat = post('type', 'rachat');
$po = set_po(post('po'));


if(empty($ref)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;
if(!$MPersoItem->setVendu($ref, $typeAchat, $po)) {ErrorView(500);}
?>