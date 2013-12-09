<?php
$search = html_entity_decode(post('search'), ENT_COMPAT, 'UTF-8');
$type = post('params', 'all');

//echo $search;
//var_dump($search);

if(is_null($search)) {exit;}
if(is_null($type)) {$type = 'stat';} //Pas de juste item.

$MItem = new \modeles\Item;
$MStat = new \modeles\Stat;

$result = array();

$resultStat = $MStat->search($search);

foreach($resultStat as $val)
{
	$result[] = array(
		'id' => 'S'.$val['idStat'], 
		'value' => $val['nom'],
		'text' => $val['nom']
	);
}

if($type == 'all')
{
	$resultItem = $MItem->search($search);
	
	foreach($resultItem as $val)
	{
		//Si doublon, privilégie les stats.
		if(!in_array($val, $result))
		{ 
			$result[] = array(
				'id' => 'I'.$val['id'],
				'value' => '<span style="color: #'.$val['color'].';">'.$val['text'].'</span>',
				'text' => $val['text']
			);
		}
	}
}

if(count($result) > 15) {array_splice($result, 15);}

foreach($result as $key => $val)
{
	$result[$key]['value'] = str_replace('#ffffff', '#000000', $result[$key]['value']);
	$result[$key]['value'] = str_replace('#00ff00', '#00C000', $result[$key]['value']);
}


echo json_encode($result);
?>