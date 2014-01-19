<?php
$Params_head['css'] = array($base_url.'/css/login.css');
$Params_head['js'] = array($base_url.'/js/login.js');
require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('login.html');
$TPL->End();

require_once('footer.php');
?>