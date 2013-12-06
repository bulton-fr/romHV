<?php
$login = post('login');
$pwd = post('pwd');

if(!is_null($login) && !is_null($pwd))
{
	//La personne cherche à se connecter.
	
	$modele_user = new \modules\users\modeles\users;
	
	//Si connecté
	$DefaultControler = 'index';
}