<?php
$TPL = new \BFW_Tpl\Classes\Template('perso/addItem.html');

$Form = new \BFW\CKernel\Form('FormAddItem');
$token = $Form->create_token();

$TPL->AddVars(array('token' => $token));

$TPL->End();
?>