<?php
$block = post('block');

if(is_null($block)) {ErrorView(500);}


if($block == 'poUser')
{
    $MUser = new \modules\users\modeles\Users;
    $mesPo = $MUser->getPo($idUser);
    
    $echo = get_po($mesPo);
}
elseif($block == 'listPerso')
{
    $MPerso = new \modeles\Perso();
    $persos = $MPerso->getAll($idUser);
    
    $echo = json_encode($persos);
}
else {ErrorView(500);}

ob_clean();
echo $echo;