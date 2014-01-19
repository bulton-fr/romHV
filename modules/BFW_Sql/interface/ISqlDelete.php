<?php
/**
 * Interface en rapport avec la classe SqlDelete
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW_Sql\Interfaces;

/**
 * Interface de la classe SqlDelete
 * @package BFW_Sql
 */
interface ISqlDelete
{
	/**
	 * Constructeur
	 * 
	 * @param Sql    $Sql   : (référence) L'instance Sql
	 * @param string $table : La table sur laquelle agir
	 */
	public function __construct(&$Sql, $table);
	
	/**
	 * On assemble la requête
	 */
	public function assembler_requete();
	
	/**
	 * Permet de déclarer une requête DELETE
	 * 
	 * @param string $table : La table sur laquelle agir
	 * 
	 * @return Sql_Delete : L'instance de l'objet courant.
	 */
	public function delete($table);
}
?>