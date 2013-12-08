<?php
$TPL = new \BFW_Tpl\Classes\Template('perso/view.html');

$MPerso = new \modeles\Perso;

$idPerso = post('idPerso');
if(is_null($idPerso)) {echo 'Désolé j\'ai crashé.';}

$verif = $MPerso->persoBeUser($idPerso, $idUser);
if($verif == false) {echo 'Désolé j\'ai crashé.';}

$perso = $MPerso->getPerso($idPerso);
$perso['po'] = get_po($perso['po']);

$MPersoItem = new \modeles\PersoItem();
$perso['nbVente'] = $MPersoItem->getNbVentePerso($idPerso);
$perso['nbAttente'] = $MPersoItem->getNbAttentePerso($idPerso);

$TPL->AddVars($perso);

$TPL->End();