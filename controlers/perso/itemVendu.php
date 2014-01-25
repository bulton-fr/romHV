<?php
$ref = post('ref', '');
$typeAchat = post('type', 'rachat');
$po = set_po(post('po', 0));


if(empty($ref)) {ErrorView(400);}

$MPersoItem = new \modeles\PersoItem;
$persoItem = $MPersoItem->getPersoItem($ref);

if($idUser == $persoItem['idUser'])
{
    if($MPersoItem->setVendu($ref, $typeAchat, $po))
    {
        $MPerso = new \modeles\Perso;
        $oldPoPerso = $MPerso->getPo((int) $persoItem['idPerso']);
        $poPerso = $oldPoPerso + $po;
        
        $MPerso->setPo((int) $persoItem['idPerso'], $poPerso);
    }
    else {ErrorView(500);}
}
else {ErrorView(500);}
?>