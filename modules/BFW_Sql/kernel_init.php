<?php
$ClassLoaderModule_BFWSql = new SplClassLoader('BFW_Sql\Classes', $path.'modules/BFW_Sql/class');
$ClassLoaderModule_BFWSql->register();

$InterLoaderModule_BFWSql = new SplClassLoader('BFW_Sql\Interfaces', $path.'modules/BFW_Sql/interface');
$InterLoaderModule_BFWSql->register();


require_once($path.'/modules/BFW_Sql/config.php');

if($bd_enabled)
{
	$DB = new \BFW_Sql\Classes\SqlConnect($bd_host, $bd_user, $bd_pass, $bd_name, $bd_type);
	$bd_host = $bd_user = $bd_pass = 'bouh le vilain hacker !';
	
	if($Kernel->get_debug())
	{
		$observerSql = new \BFW_Sql\Classes\SqlObserver;
		$Kernel->attachOther($observerSql);
	}
}
else
{
	$DB = null;
}
?>