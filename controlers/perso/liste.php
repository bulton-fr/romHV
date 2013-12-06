<?php
$TPL = new \BFW_Tpl\Classes\Template('perso_liste.html');
$TPL->AddGeneralVars(array('path' => $path));

$MPerso = new \modeles\Perso();
$persos = $MPerso->recupAll($idUser);

if(count($persos) > 0)
{
	foreach($persos as $perso)
	{
		$perso['po'] = number_format($perso['po'], 0, '', '.');
		$TPL->AddBlockWithEnd('persos', $perso);
	}
}
else {$TPL->AddBlockWithEnd('noPerso');}

$TPL->End();
?>