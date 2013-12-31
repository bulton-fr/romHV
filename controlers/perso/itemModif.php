<?php
$ref = post('ref', '');
$perso = (int) post('perso');
$enchere = set_po(post('enchere', 0));
$rachat = set_po(post('rachat', 0));
$Uenchere = set_po(post('Uenchere', 0));
$Urachat = set_po(post('Urachat', 0));
$Unb = (int) post('Unb', 0);

if(empty($ref)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;

$item = $MPersoItem->getPersoItem($ref);
if(count($item) == 0) {ErrorView(500);}

$idPersoBdd = (int) $item['idPerso'];

if($idPersoBdd != $perso)
{
	if(!$MPersoItem->movePerso($ref, $perso)) {ErrorView(500);}
}

if(!$MPersoItem->editVente($ref, $enchere, $rachat, $Uenchere, $Urachat, $Unb)) {ErrorView(500);}
?>