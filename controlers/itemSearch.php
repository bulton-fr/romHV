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
		'text' => $val['nom'],
		'color' => get_color_item('ffffff')
	);
}

if($type == 'all')
{
	$resultTmp = $result;
	$result = array();
	
	$resultItemStart = $MItem->search($search, false, true);
	
	foreach($resultItemStart as $val)
	{
		//Si doublon, privilégie les stats.
		if(!in_array($val, $result))
		{
			$val['color'] = get_color_item($val['color']);
			$result[] = array(
				'id' => 'I'.$val['id'],
				'value' => '<span style="color: #'.$val['color'].';">'.$val['text'].'</span>',
				'text' => $val['text'],
				'color' => $val['color']
			);
		}
	}
	$result = array_merge($result, $resultTmp);
	
	
	$resultItem = $MItem->search($search);
	
	foreach($resultItem as $val)
	{
		//Si doublon, privilégie les stats.
		if(!in_array($val, $result))
		{
			$val['color'] = get_color_item($val['color']); 
			$result[] = array(
				'id' => 'I'.$val['id'],
				'value' => '<span style="color: #'.$val['color'].';">'.$val['text'].'</span>',
				'text' => $val['text'],
				'color' => $val['color']
			);
		}
	}
}

if(count($result) > 20) {array_splice($result, 20);}


echo json_encode($result);
?>