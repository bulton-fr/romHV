<?php
$Params_head = array(
	'css' => array(
		$path.'jquery_ui/themes/redmond/jquery-ui.css',
		$path.'jquery_ui/timepicker/timepicker.css',
		$path.'farbtastic/farbtastic.css',
		$path.'css/index.css',
		$path.'css/recap.css',
		$path.'css/vendu.css',
		$path.'css/ventes.css',
		$path.'css/enAttente.css',
		$path.'css/perso.css',
		$path.'css/compte.css'
	),
	'js' => array(
		'https://www.google.com/jsapi',
		$path.'js/index.js',
		$path.'js/perso.js',
		$path.'js/compte.js',
		$path.'js/vendu.js',
		$path.'js/ventes.js',
		$path.'js/enAttente.js',
		$path.'jquery_ui/ui/jquery-ui.min.js',
		$path.'jquery_ui/timepicker/jquery-ui-timepicker-addon.js',
		$path.'farbtastic/farbtastic.js',
		$path.'js/php.js'
	)
);

require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('index.html');
$TPL->AddGeneralVars(array('path' => $path));

$MUser = new \modules\users\modeles\Users;
$mesPo = $MUser->getPo($idUser);
$TPL->AddVars(array('mesPo' => get_po($mesPo)));


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