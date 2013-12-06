<?php
$TPL = new BFW_Tpl\Classes\Template('header.html');
$TPL->AddGeneralVars(array('path' => $path, 'base_url' => $base_url));

if(isset($Params_head) && is_array($Params_head))
{
	if(isset($Params_head['css']) && is_array($Params_head['css']))
	{
		foreach($Params_head['css'] as $link) {$TPL->AddBlock('css', array('link' => $link));}
		$TPL->EndBlock();
	}
	
	if(isset($Params_head['js']) && is_array($Params_head['js']))
	{
		foreach($Params_head['js'] as $link) {$TPL->AddBlock('js', array('link' => $link));}
		$TPL->EndBlock();
	}
}

$TPL->End();