<?php
$Params_head = array(
	'css' => array(
		$path.'css/index.css'
	),
	'js' => array(
		'https://www.google.com/jsapi',
		$path.'js/index.js',
		$path.'js/import.js',
		$path.'js/php.js'
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

$idDirParent = '0BxEHaLjVuOdeZjNTRFU2V042bFU';
$urlGDrive = 'https://googledrive.com/host/'.$idDirParent.'/';
$actionYML = 'select * from html where url="'.$urlGDrive.'" and xpath=\'//div[@class="folder-cell"]/a\'';
$urlYML = 'http://query.yahooapis.com/v1/public/yql?q='.urlencode($actionYML).'&format=json&diagnostics=true&callback=';


$listJson = file_get_contents($urlYML);
$listClass = json_decode($listJson);
$list = $listClass->query->results->a;

$urlGZip = $urlVersion = '';
foreach($list as $file)
{
	if($file->content == 'version.txt') {$urlVersion = 'https://googledrive.com'.$file->href;}
	if($file->content == 'runes.targz') {$urlGZip = 'https://googledrive.com'.$file->href;}
}

$MConfig = new \modeles\Config;
$versionBdd = $MConfig->getConfig('rom_version');
$versionServeur = file_get_contents($urlVersion);

if($versionBdd == $versionServeur) {$TPL->AddBlockWithEnd('noUpdate');}
else
{
	$TPL->AddBlockWithEnd('update', array(
		'urlVersion' => $urlVersion, 
		'urlGZip' => $urlGZip)
	);
}

$TPL->End();

require_once('footer.php');
?>