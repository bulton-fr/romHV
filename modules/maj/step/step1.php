<?php
//Sauvegarde la base de données actuelle

require(path_modules.'BFW_Sql/config.php');
$date = new \BFW\CKernel\Date;
$nameFile = 'dump_'.$date->annee.'-'.$date->mois.'-'.$date->jour.'_'.$date->heure.'-'.$date->minute.'-'.$date->seconde.'.sql.gz';

$cmd  = 'mysqldump --host='.$bd_host.' --user='.$bd_user.' --password='.$bd_pass;
$cmd .= ' --no-create-db --default-character-set=utf8 --lock-tables=FALSE --tables '.$bd_name;
$cmd .= ' | gzip > ../sauv_bdd/'.$nameFile;

if(!function_exists('system') || !system($cmd)) {ErrorView(500);}
?>