<?php
$TPL = new \BFW_Tpl\Classes\Template('issues/view.html');

$client = new \Github\Client(
    new \Github\HttpClient\CachedHttpClient(array('cache_dir' => __DIR__.'/../../modules/github/cache'))
);

$apiIssue = $client->api('issue');

try
{
    $labels = $apiIssue->labels()->all('bulton-fr', 'romHV');
}
catch(exception $e)
{
    if($Kernel->get_debug()) {var_dump($e->getMessage());}
    $labels = array();
}

foreach($labels as $labelVal)
{
    $label = $label_color = '';
    
    $label = $labelVal['name'];
    $labelColor = $labelVal['color'];
    
    $labelVal['label'] = $label;
    $labelVal['label_color'] = $labelColor;
    
    if($label == 'duplicate' || $label == 'invalid' || $label == 'Item runes') {$labelVal['label_color'] .= '; color: black';}
    
    $TPL->AddBlockWithEnd('label', $labelVal);
}

include_once(path_controler.'issues/liste.php');
$TPL->AddVars(array('listeIssue' => $tplListIssue));

$TPL->End();
?>