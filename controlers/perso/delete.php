<?php
$idPerso = post('idPerso');

if(!is_null($idPerso))
{
	$idPerso = (int) $idPerso;
	$MPerso = new \modeles\Perso();
	$MPerso->remove($idPerso);
}
else {ErrorView(400);}
?>