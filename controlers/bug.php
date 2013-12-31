<?php
$TPL = new \BFW_Tpl\Classes\Template('bug.html');

$client = new \Github\Client();

try
{
	$issues = $client->api('issue')->all('bulton-fr', 'romHV', array('state' => 'open'));
}
catch(exception $e) {$issues = array();}

foreach($issues as $issue)
{
	$tpl_issue = array();
	
	$tpl_issue['id'] = $issue['id'];
	$tpl_issue['number'] = $issue['number'];
	$tpl_issue['title'] = $issue['title'];
	$tpl_issue['created_at'] = $issue['created_at'];
	$tpl_issue['body'] = $issue['body'];
	
	$labels = $issue['labels'];
	$label = $label_color = '';
	if(isset($labels[0]))
	{
		$label = $labels[0]['name'];
		$labelColor = $labels[0]['color'];
	}
	
	$tpl_issue['label'] = $label;
	$tpl_issue['label_color'] = $labelColor;
	
	$TPL->AddBlockWithEnd('issue', $tpl_issue);
}

if(count($issues) == 0) {$TPL->AddBlockWithEnd('ErrorGetIssue');}

$TPL->End();
?>