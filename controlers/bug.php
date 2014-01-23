<?php
$TPL = new \BFW_Tpl\Classes\Template('bug.html');

$client = new \Github\Client(
    new \Github\HttpClient\CachedHttpClient(array('cache_dir' => __DIR__.'/../modules/github/cache'))
);

$apiIssue = $client->api('issue');

try
{
	$issues = $apiIssue->all('bulton-fr', 'romHV', array('state' => 'open'));
}
catch(exception $e)
{
    if($Kernel->get_debug()) {var_dump($e->getMessage());}
    $issues = array();
}

//echo '<pre>';print_r($issues);
foreach($issues as $issue)
{
    $tpl_issue = array();
	
	$tpl_issue['url'] = $issue['html_url'];
	$tpl_issue['id'] = $issue['id'];
	$tpl_issue['number'] = $issue['number'];
	$tpl_issue['title'] = $issue['title'];
	$tpl_issue['created_at'] = $issue['created_at'];
	$tpl_issue['body'] = nl2br($issue['body']);
	
	$labels = $issue['labels'];
	$label = $label_color = '';
	if(isset($labels[0]))
	{
		$label = $labels[0]['name'];
		$labelColor = $labels[0]['color'];
	}
	
	$tpl_issue['label'] = $label;
	$tpl_issue['label_color'] = $labelColor;
	
	$TPL->AddBlock('issue', $tpl_issue);
    
    $lists = array();
    if($issue['comments'] > 0)
    {
        try
        {
            $comments = $apiIssue->comments()->all('bulton-fr', 'romHV', (int) $issue['number']);
            
            foreach($comments as $comment)
            {
                //echo '<pre>';print_r($comment);echo '</pre>';
                $dateUp = $comment['updated_at'];
                $exDateUp = explode('T', $dateUp);
                $date = $exDateUp[0];
                $heure = substr($exDateUp[1], 0, -1);
                
                $exDate = explode('-', $date);
                $exHeure = explode(':', $heure);
                
                $time = mktime($exHeure[0], $exHeure[1], $exHeure[2], $exDate[1], $exDate[2], $exDate[0]);
                $lists[$time] = array(
                    'type' => 'comment',
                    'user' => $comment['user'],
                    'body' => $comment['body'],
                    'commit_id' => $event['commit_id'],
                    'commit' => substr($event['commit_id'], 0, 10)
                );
            }
        }
        catch(exception $e)
        {
            if($Kernel->get_debug()) {var_dump($e->getMessage());}
        }
    }
    
    try
    {
        $events = $apiIssue->events()->all('bulton-fr', 'romHV', (int) $issue['number']);
        //if($issue['number'] == 30) {echo '<pre>';print_r($events);echo '</pre>';}
        foreach($events as $event)
        {
            $dateUp = $event['created_at'];
            $exDateUp = explode('T', $dateUp);
            $date = $exDateUp[0];
            $heure = substr($exDateUp[1], 0, -1);
            
            $exDate = explode('-', $date);
            $exHeure = explode(':', $heure);
            
            $time = mktime($exHeure[0], $exHeure[1], $exHeure[2], $exDate[1], $exDate[2], $exDate[0]);
            
            if(isset($lists[$time])) {$time++;}
            $lists[$time] = array(
                'type' => 'event',
                'event' => $event['event'],
                'user' => $event['actor'],
                'body' => '',
                'commit_id' => $event['commit_id'],
                'commit' => substr($event['commit_id'], 0, 10)
            );
        }
    }
    catch(exception $e)
    {
        if($Kernel->get_debug()) {var_dump($e->getMessage());}
    }
    
    ksort($lists);
    
    if(count($lists) > 0)
    {
        foreach($lists as $time => $list)
        {
            //echo '<pre>';print_r($list);echo '</pre>';
            $event = (isset($list['event'])) ? '_'.$list['event'] : '';
            $date = new \BFW\CKernel\Date(date('Y-m-d H:i:sO', $time));
            $user = (is_array($list['user'])) ? $list['user']['login'] : '';
            
            $TPL->AddBlock('CommentEvent');
                $TPL->AddBlock($list['type'].$event, array(
                    'user' => $user,
                    'body' => $list['body'],
                    'date' => $date->aff_simple(),
                    'commit_id' => $list['commit_id'],
                    'commit' => $list['commit']
                ));
                $TPL->remonte();
            $TPL->remonte();
        }
    }
    
    $TPL->EndBlock();
}

if(count($issues) == 0) {$TPL->AddBlockWithEnd('ErrorGetIssue');}

$TPL->End();
?>