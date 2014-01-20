<?php

if(!isset($base_url))
{
    echo '<pre>';print_r(debug_backtrace());die;
}


$Params_head = array(
	'css' => array(
		$base_url.'/jquery_ui/themes/redmond/jquery-ui.css',
		$base_url.'/jquery_ui/timepicker/timepicker.css',
		$base_url.'/farbtastic/farbtastic.css',
		$base_url.'/css/index.css',
		$base_url.'/css/recap.css',
		$base_url.'/css/vendu.css',
		$base_url.'/css/ventes.css',
		$base_url.'/css/enAttente.css',
		$base_url.'/css/perso.css',
		$base_url.'/css/compte.css',
		$base_url.'/css/bug.css'
	),
	'js' => array(
		'https://www.google.com/jsapi',
		$base_url.'/js/index.js',
		$base_url.'/js/perso.js',
		$base_url.'/js/compte.js',
		$base_url.'/js/vendu.js',
		$base_url.'/js/ventes.js',
		$base_url.'/js/enAttente.js',
		$base_url.'/js/bug.js',
		$base_url.'/jquery_ui/ui/jquery-ui.min.js',
		$base_url.'/jquery_ui/timepicker/jquery-ui-timepicker-addon.js',
		$base_url.'/farbtastic/farbtastic.js',
		$base_url.'/js/php.js'
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