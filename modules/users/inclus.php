<?php
$login = post('login');
$mdp = post('mdp');

if(!is_null($login) && !is_null($mdp))
{
	$logged = false;
	//La personne cherche à se connecter.
	
	$MUser = new \modules\users\modeles\Users;
	
	$idUser = $MUser->IdFromLogin($login);
	if($idUser != false)
	{
		$mdpBDD = $MUser->getMdp($idUser);
		$mdp = hashage($mdp);
		
		if($mdp == $mdpBDD)
		{
			$logged = true;
		}
	}
	
	if($logged)
	{
		$_SESSION['logged'] = true;
		$_SESSION['idUser'] = $idUser;
		$_SESSION['Login'] = $login;
		
		$BackColor = $MUser->getBackgroundColor($idUser);
		$BackOpacity = $MUser->getBackgroundOpacity($idUser);
		$TextColorBlack = $MUser->getColorTextBlack($idUser);
		
		if($BackColor == false || is_null($BackColor)) {$BackColor = '#ffffff';}
		if($BackOpacity == false || is_null($BackOpacity)) {$BackOpacity = '93';}
		if($TextColorBlack == false) {$TextColorBlack = 'black';}
		
		$Memcache->setVal('U'.$idUser.'_BackColor', $BackColor);
		$Memcache->setVal('U'.$idUser.'_BackOpacity', $BackOpacity);
		$Memcache->setVal('U'.$idUser.'_TextColorBlack', $TextColorBlack);
		
		ob_clean();
		echo json_encode(array('status' => 200, 'login' => $login));
		exit;
	}
	else
	{
		ErrorView(403);
	}
}

if($request == '/deco')
{
	session_destroy();
	
	ob_clean();
	echo 'RedirectLogin'; //Instruction pour le js en sortie d'ajax
	
	exit;
}

if(!isset($_SESSION['logged']) && !($request == '/' || $request == '/login'))
{
	header('Location: /');
}

if(isset($_SESSION['logged']) && $_SESSION['logged'] == true)
{
	//Si connecté
	$DefaultControler = 'index';
	$idUser = $_SESSION['idUser'];
}
