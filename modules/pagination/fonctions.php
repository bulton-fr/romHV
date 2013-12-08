<?php
function pagination($nb, &$pa, $link='', $nb_par_page=10)
{
	$return = array();
	
	$nb_page = ceil($nb/$nb_par_page);
	
	if($nb_page < $pa) {$pa = $nb_page;}
	
	$pt_deb = $pt_end = false;
	$aff_deb = $aff_center = $aff_end = false;
	
	$url = explode('[page]', $link);
	if($pa > $nb_page) {$nb = $nb_page;}
	
	$return['html']['infos'] = 'Page '.$pa.' sur '.$nb_page;
	$return['pa'] = $pa;
	$return['nb_page'] = $nb_page;
	
	if($nb_page > 1)
	{
		$return['html']['nav'] = '<ul id="pagination">';
		for($i=1;$i<=$nb_page;$i++)
		{
			if(
				($i-1 == $pa || $i == $pa || $i+1 == $pa) || 
				(
					($i-1 == 1 && $i+2 != $pa) || 
					$i == 1 || 
					($i+1 == $nb_page && $i-2 != $pa) || 
					$i == $nb_page
				)
			)
			{
				if($i == $pa) {$class_courant = ' pagination_courant';}
				else {$class_courant = '';}
				
					if($i == 1) {$return['html']['nav'] .= '<li class="pagination_deb'.$class_courant.'">';}
				elseif($i == $nb_page) {$return['html']['nav'] .= '<li class="pagination_end'.$class_courant.'">';}
				else {$return['html']['nav'] .= '<li class="pagination_center'.$class_courant.'">';}
				
				if($i != $pa) {$return['html']['nav'] .= '<a href="'.$url[0].$i.$url[1].'">'.$i.'</a></li>';}
				else {$return['html']['nav'] .= $i.'</li>';}
				
				if($aff_deb == false) {$aff_deb = true;}
				if($aff_center == false && $pt_deb == true) {$aff_center = true;}
				if($aff_end == false && $pt_end == true) {$aff_end = true;}
			}
			else
			{
				if($aff_deb == true && $pt_deb == false)
				{
					$return['html']['nav'] .= '<li class="pagination_pt">...</li>';
					$pt_deb = true;
				}
				if($aff_center == true && $pt_end == false)
				{
					$return['html']['nav'] .= '<li class="pagination_pt">...</li>';
					$pt_end = true;
				}
			}
		}
		
		$return['html']['nav'] .= '</ul>';
	}
	else {$return['html']['nav'] = '';}
	
	return $return;
}

function paginationHTML($nb, &$pa, $link='', $nb_par_page=10)
{
	$ret = pagination($nb, $pa, $link);
	
	return $ret['html'];
}