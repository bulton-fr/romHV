<?php
$Params_head = array(
	'css' => array(
		$path.'css/index.css',
		//$path.'jquery_ui/themes/redmond/jquery-ui.css'
	),
	'js' => array(
		$path.'js/index.js',
		//$path.'jquery_ui/ui/jquery-ui.min.js'
	)
);

require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('index.html');

$TPL->AddVars(array('mesPo' => '25.000.000'));

$TPL->End();

require_once('footer.php');
?>