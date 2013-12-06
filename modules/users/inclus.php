<?php
$login = post('login');
$mdp = post('mdp');

if(!is_null($login) && !is_null($mdp))
{
	$logged = false;
	//La personne cherche à se connecter.
	
	$modele_user = new \modules\users\modeles\users;
	
	$idUser = $modele_user->IdFromLogin($login);
	if($idUser != false)
	{
		$mdpBDD = $modele_user->getMdp($idUser);
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
		
		ob_clean();
		echo json_encode(array('status' => 200, 'login' => $login));
		exit;
	}
	else
	{
		ErrorView(403);
	}
}

if(isset($_SESSION['logged']) && $_SESSION['logged'] == true)
{
	//Si connecté
	$DefaultControler = 'index';
}
