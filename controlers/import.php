<?php
$Memcache->delete('majStep2Start');

$Params_head = array(
	'css' => array(
		$base_url.'/css/index.css'
	),
	'js' => array(
		'https://www.google.com/jsapi',
		$base_url.'/js/index.js',
		$base_url.'/js/import.js',
		$base_url.'/js/php.js'
	)
);

require_once('header.php');

$TPL = new \BFW_Tpl\Classes\Template('import.html');
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

$MConfig = new \modeles\Config;
$versionBdd = $MConfig->getConfig('rom_version');

$idDirParent = '0BxEHaLjVuOdeZjNTRFU2V042bFU';
$urlGDrive = 'https://googledrive.com/host/'.$idDirParent.'/';
$actionYML = 'select * from html where url="'.$urlGDrive.'" and xpath=\'//div[@class="folder-cell"]/a\'';
$urlYML = 'http://query.yahooapis.com/v1/public/yql?q='.urlencode($actionYML).'&format=json&diagnostics=true&callback=';

try
{
	$listJson = file_get_contents($urlYML);
	$listClass = json_decode($listJson);
	$list = $listClass->query->results->a;
	
	$urlGZip = $urlVersion = '';
	foreach($list as $file)
	{
		if($file->content == 'version.txt') {$urlVersion = 'https://googledrive.com'.$file->href;}
		if($file->content == 'runes.targz') {$urlGZip = 'https://googledrive.com'.$file->href;}
	}
	
	$versionServeur = file_get_contents($urlVersion);
}
catch(exception $e)
{
	$versionServeur = $versionBdd;
	$TPL->AddBlockWithEnd('errorRecupInfos');
}

if($versionBdd == $versionServeur) {$TPL->AddBlockWithEnd('noUpdate');}
else
{
	$Memcache->setVal('MajUrlVersion', $urlVersion);
	$Memcache->setVal('MajVersion', $versionServeur);
	$Memcache->setVal('MajUrlGZip', $urlGZip);
	
	$TPL->AddBlockWithEnd('update', array(
		'urlVersion' => $urlVersion, 
		'urlGZip' => $urlGZip)
	);
}

$TPL->End();

require_once('footer.php');
?>