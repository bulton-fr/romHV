<?php
require_once('header.php');

$tpl = new \BFW_Tpl\Classes\Template('conflict.html');

$revolveMode = get(0, false);
if($revolveMode == 'resolve') {$revolveMode = true;}
else {$revolveMode = false;}

$MPersoItem = new \modeles\PersoItem;
$all = $MPersoItem->all();

$conflict = array();
foreach($all as $pItem)
{
    $idPerso = (int) $pItem['idPerso'];
    $ref = $pItem['ref'];
    
    //$ref = 'U'.$idUser.'P'.$idPerso.$typeItem.$idItem.'N'.$nb;
    $extractRefItem = substr($ref, (strpos($ref, 'P')+1), (strpos($ref, 'I') - strpos($ref, 'P') -1));
    $extractRefStat = substr($ref, (strpos($ref, 'P')+1), (strpos($ref, 'S') - strpos($ref, 'P') -1));
    
    if(strpos($extractRefItem, 'S')) {$extractRef = (int) $extractRefStat;}
    if(strpos($extractRefStat, 'I')) {$extractRef = (int) $extractRefItem;}
    
    if(($idPerso != $extractRef) || is_null($extractRef))
    {
        if($revolveMode === true)
        {
            if(empty($pItem['typeItem'])) {$pItem['typeItem'] = 'I';}
            $MPersoItem->majIdItem($ref, $pItem['typeItem'].$pItem['idItem']);
        }
        
        $conflict[] = $pItem;
    }
}

if(count($conflict) == 0) {echo 'Aucun conflit détecté :)'; exit;}

$tpl->AddVars(array(
    'nbConflict' => count($conflict),
    'nbTotalPItem' => count($all),
    'pourcent' => round(((count($conflict)*100)/count($all)), 2)
));

foreach($conflict[0] as $column => $val) {$tpl->AddBlockWithEnd('conflictColumn', array('nameColumn' => $column));}

foreach($conflict as $pitem)
{
    $tpl->AddBlock('conflicts');
    foreach($pitem as $val)
    {
        $tpl->AddBlock('conflict', array('val' => nl2br(stripslashes($val))));
        $tpl->remonte();
    }
    $tpl->EndBlock();
}

$tpl->End();
?>