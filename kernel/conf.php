<?php
/**
 * Toute la configuration du framework
 * @author Vermeulen Maxime
 * @package BFW
 */

//*** Base De Données ***
$bd_enabled = true; //Permet d'activer ou non la partie SQL
$bd_module = 'BFW_Sql';
//*** Base De Données ***

//*** Template ***
$tpl_module = 'BFW_Template';
//*** Template ***

//*** Controler ***
$ctr_module = 'BFW_Controler';
$ctr_class = false;
$ctr_defaultMethode = 'index'; //La méthode à appeler si aucune n'est définie dans l'url (pour tous les contrôleurs)
//*** Controler ***

//*** Adresse ***
$base_url = 'http://romhv';
//*** Adresse ***

//*** Controler par défaut ***
$DefaultControler = 'login'; //Il s'agit du modele de page qui sera utilisé comme page index du site
//*** Controler par défaut ***
?>