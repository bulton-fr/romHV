<?php
/**
 * Fichier appelé depuis le kernel si BFW_Sql est utilisé pour le système de connexion SGBD
 * 
 * Permet l'initialisation du système d'SGBD
 * 
 * @author Vermeulen Maxime
 * @version 1.0.1
 * @package BFW_Sql
 */

$ClassLoaderModule_BFWSql = new SplClassLoader('BFW_Sql\Classes', $path.'modules/BFW_Sql/class');
$ClassLoaderModule_BFWSql->register();

$InterLoaderModule_BFWSql = new SplClassLoader('BFW_Sql\Interfaces', $path.'modules/BFW_Sql/interface');
$InterLoaderModule_BFWSql->register();


require_once($path.'modules/BFW_Sql/config.php');

if($bd_enabled)
{
    try
    {
    	$DB = new \BFW_Sql\Classes\SqlConnect($bd_host, $bd_user, $bd_pass, $bd_name, $bd_type);
    	$bd_host = $bd_user = $bd_pass = 'bouh le vilain hacker !';
    	
    	if($bd_observer)
    	{
    		$observerSql = new \BFW_Sql\Classes\SqlObserver;
    		$Kernel->attachOther($observerSql);
    	}
    }
    catch(exception $e) {ErrorView($e->getMessage());}
}
else
{
	$DB = null;
}
?>