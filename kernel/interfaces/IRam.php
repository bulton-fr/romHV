<?php

namespace BFW\IKernel;

interface IRam
{
	/**
	 * Constructeur
	 * Se connecte au serveur memcache indiqué, par défaut au localhost
	 * @param string [optionel] le nom du serveur memcache
	 * @return bool [optionel] : Renvoi false si l'extension memcached n'est pas installé
	 */
	public function __construct($name='localhost');
	
	/**
	 * Permet de retourner la valeur d'une clé. Si la clé n'existe pas, on la met en mémoire
	 * @param string : Clé correspondant à la valeur
	 * @param mixed : Les nouvelles données
	 * @param int [optionnel] Le temps en seconde avant expiration. 0 illimité, max 30jours
	 * @return mixed La valeur demandée
	 */
	public function val($key, $data, $expire=0);
	
	/**
	 * On modifie le temps avant expiration des infos sur le serveur memcached pour une clé choisie.
	 * @param string la clé disignant les infos concerné
	 * @param int le nouveau temps avant expiration (0: pas d'expiration, max 30jours)
	 */
	public function maj_expire($key, $exp);
	
	/**
	 * Modifie les données pour une clé
	 * Toutes les anciennes données seront écrasé par les nouvelles.
	 * @param string : La clé
	 * @param mixed : Les nouvelles données
	 * @param int [optionnel] Le temps en seconde avant expiration. 0 illimité, max 30jours
	 */
	public function maj_data($key, $data, $expire=0);
	
	/**
	 * Permet de savoir si la clé existe
	 * @param string la clé disignant les infos concernées
	 * @return bool True si la clé existe, False sinon
	 */
	public function if_val_exists($key);
	
	/**
	 * Supprime une clé
	 * @param string la clé disignant les infos concernées
	 * @return bool True si la suppression à réussi, False sinon
	 */
	public function delete($key);
	
	/**
	 * Accesseur vers l'attribut $lastVal
	 * @return string : La valeur de l'attribut
	 */
	public function get_lastVal();
	
	/**
	 * Accesseur vers l'attribut $lastKey
	 * @return string : La valeur de l'attribut
	 */
	public function get_lastKey();
	
	/**
	 * Met à jour les attributs lastVal et lastKey
	 * @param string $key : La dernière clé
	 * @param string $data : La dernière val
	 */
	public function maj_lasts($key, $data);
}
?>