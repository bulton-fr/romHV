<?php
$TPL = new \BFW_Tpl\Classes\Template('recap.html');

$MPerso = new \modeles\Perso();
$persos = $MPerso->getAllWithVente($idUser);

if(count($persos) > 0)
{
	foreach($persos as $perso)
	{
		$perso['po'] = get_po($perso['po']);
		$TPL->AddBlockWithEnd('persos', $perso);
	}
}

$MConfig = new \modeles\Config;
$romVersion = $MConfig->getConfig('rom_version');
$TPL->AddVars(array('romVersion' => $romVersion));

$TPL->End();
?>