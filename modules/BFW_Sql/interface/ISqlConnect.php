<?php
/**
 * Interface en rapport avec la classe SqlConnect
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW_Sql\Interfaces;

/**
 * Interface de la classe SqlConnect
 * @package BFW_Sql
 */
interface ISqlConnect
{
	/**
	 * Constructeur de la classe. Créer la connexion
	 * 
	 * @param string $host  : Adresse du serveur hôte
	 * @param string $login : Le login de connexion
	 * @param string $passe : Le mot de passe de connexion
	 * @param string $base  : Le nom de la base à laquelle se connecter
	 * @param string $type  : [optionnel] Le type de base à laquel on se connexion (pgsql/mysql/etc) au format de pdo
	 * @param bool   $utf8  : [optionnel] Si on a la base en utf8 ou non (par défaut : true)
	 */
	public function __construct($host, $login, $passe, $base, $type='mysql', $utf8=true);
	
	/**
	 * Permet de protéger une requête contre les injections et autres.
	 * 
	 * @param string $string : la requêtre sql
	 * 
	 * @return string la requête sql protégé
	 */
	public function protect($string);
	
	/**
	 * Accesseur pour accéder à $this->PDO
	 * 
	 * @return PDO : Instance de la classe PDO
	 */
	public function getPDO();
	
	/**
	 * Accesseur pour accéder à $this->nb_query
	 * 
	 * @return int : Le nombre de requête
	 */
	public function getNbQuery();
}
?>