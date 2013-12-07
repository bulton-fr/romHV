<?php
$TPL = new \BFW_Tpl\Classes\Template('recap.html');

$MPerso = new \modeles\Perso();
$persos = $MPerso->getAllWithVente($idUser);

if(count($persos) > 0)
{
	foreach($persos as $perso)
	{
		$perso['po'] = number_format($perso['po'], 0, '', '.');
		$TPL->AddBlockWithEnd('persos', $perso);
	}
}

$TPL->End();
?>