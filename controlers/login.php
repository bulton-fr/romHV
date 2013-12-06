<?php
$Params_head['css'] = array('login.css');
$Params_head['js'] = array($path.'js/login.js');
require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('login.html');
$TPL->End();

require_once('footer.php');
?>