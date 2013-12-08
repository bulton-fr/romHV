<?php
$TPL = new \BFW_Tpl\Classes\Template('perso_liste.html');
$TPL->AddGeneralVars(array('path' => $path));

$MPerso = new \modeles\Perso();
$persos = $MPerso->getAll($idUser);

if(count($persos) > 0)
{
	foreach($persos as $perso)
	{
		$perso['po'] = get_po($perso['po']);
		$TPL->AddBlockWithEnd('persos', $perso);
	}
}
else {$TPL->AddBlockWithEnd('noPerso');}

$TPL->End();
?>