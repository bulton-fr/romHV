<?php
$majRomVersion = $Memcache->getVal('MajVersion');
if($majRomVersion === false) {ErrorView(500);}

$MConfig = new \modeles\Config;
if(!$MConfig->maj('rom_version', $majRomVersion)) {ErrorView(500);}
?>