<?php
$ref = post('ref', '');
$enchere = set_po(post('enchere', 0));
$rachat = set_po(post('rachat', 0));
$date = dateFr2Us(post('date', ''));
$duree = (int) post('duree', 1);

if(empty($ref) || empty($date)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;
if(!$MPersoItem->setVente($ref, $enchere, $rachat, $date, $duree)) {ErrorView(500);}
?>