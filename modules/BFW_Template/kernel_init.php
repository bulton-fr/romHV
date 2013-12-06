<?php
$ClassLoaderModule_BFWTpl = new SplClassLoader('BFW_Tpl\Classes', $path.'modules/BFW_Template/class');
$ClassLoaderModule_BFWTpl->register();

$InterLoaderModule_BFWTpl = new SplClassLoader('BFW_Tpl\Interfaces', $path.'modules/BFW_Template/interface');
$InterLoaderModule_BFWTpl->register(); 
?>