<?php
/**
 * Interface en rapport avec la classe SqlUpdate
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW_Sql\Interfaces;

/**
 * Interface de la classe SqlUpdate
 * @package BFW_Sql
 */
interface ISqlUpdate
{
	/**
	 * Constructeur
	 * 
	 * @param Sql    $Sql    : Référence vers l'instance Sql
	 * @param string $table  : La table sur laquelle agir
	 * @param array  $champs : Les données à modifier : array('champSql' => 'données');
	 */
	public function __construct(&$Sql, $table, $champs);
	
	/**
	 * On assemble la requête
	 */
	public function assembler_requete();
	
	/**
	 * Permet de déclarer une requête UPDATE
	 * 
	 * @param string $table  : La table sur laquelle agir
	 * @param array  $champs : Les données à modifier : array('champSql' => 'données');
	 * 
	 * @return Sql_Update : L'instance de l'objet courant.
	 */
	public function update($table, $champs);
	
	/**
	 * Permet d'ajouter d'autres données à ajouter
	 * 
	 * @param array $champs : Les données à ajouter : array('champSql' => 'données');
	 * 
	 * @return Sql_Update : L'instance de l'objet courant.
	 */
	public function addChamps($champs);
}
?>