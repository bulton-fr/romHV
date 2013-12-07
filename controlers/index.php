<?php
$Params_head = array(
	'css' => array(
		$path.'css/index.css',
		$path.'css/recap.css'
		//$path.'jquery_ui/themes/redmond/jquery-ui.css'
	),
	'js' => array(
		'https://www.google.com/jsapi',
		$path.'js/index.js',
		$path.'js/perso.js',
		$path.'js/compte.js'
		//$path.'jquery_ui/ui/jquery-ui.min.js'
	)
);

require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('index.html');
$TPL->AddGeneralVars(array('path' => $path));

$MUser = new \modules\users\modeles\Users;
$mesPo = $MUser->getPo($idUser);
$TPL->AddVars(array('mesPo' => number_format($mesPo, 0, '', '.')));


$MPerso = new \modeles\Perso();
$persos = $MPerso->getAll($idUser);

if(count($persos) > 0)
{
	foreach($persos as $perso)
	{
		$TPL->AddBlockWithEnd('persos', $perso);
	}
}


$TPL->End();

require_once('footer.php');
?>