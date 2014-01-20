<?php
/**
 * Interface en rapport avec la classe Sql
 * @author Vermeulen Maxime
 * @version 1.0
 */

namespace BFW_Sql\Interfaces;

/**
 * Interface de la classe Sql
 * @package BFW_Sql
 */
interface ISql
{
	/**
	 * Renvoi la valeur d'un attribut
	 * 
	 * @param string $name : Le nom de l'argument
	 */
	public function __get($name);
	
	//public function __construct(&$DB_connect=null);
	//Commenté car sinon Fatal error: Declaration of BFW_Sql\Classes\Modeles::__construct() must be compatible with that of BFW_Sql\Interfaces\ISql::__construct()
	
	/**
	 * Modifie le nom de la table sur laquelle on travail
	 * 
	 * @param string $name : le nom de la table
	 */
	public function set_modeleName($name);
	
	/**
	 * Renvoi l'id du dernier élément ajouté en bdd
	 * 
	 * @param string $name : [opt] nom de la séquence pour l'id (PostgreSQL)
	 * 
	 * @return int : l'id
	 */
	public function der_id($name=NULL);
	
	/**
	 * Renvoi l'id du dernier élément ajouté en bdd pour une table sans Auto Incrément
	 * 
	 * @param string      $table   : La table
	 * @param string      $champID : Le nom du champ correspondant à l'id
	 * @param strng/array $order   : Les champs sur lesquels se baser
	 * @param strng/array $where   : Clause where
	 * 
	 * @return int : l'id
	 */
	public function der_id_noAI($table, $champID, $order, $where='');
	
	/**
	 * Créer une instance de Sql_Select permettant de faire une requête de type SELECT
	 * 
	 * @param string $type : (array|objet|object) Le type de retour qui sera à faire pour les données. Par tableau en tableau.
	 * 
	 * @return Sql_Select : L'instance de l'objet Sql_Select créé
	 */
	public function select($type='array');
	
	/**
	 * Créer une instance de Sql_Insert permettant de faire une requête de type INSERT INTO
	 * 
	 * @param string $table  : [opt] La table sur laquelle agir
	 * @param array  $champs : [opt] Les données à ajouter : array('champSql' => 'données');
	 * 
	 * @return Sql_Insert : L'instance de l'objet Sql_Select créé
	 */
	public function insert($table=null, $champs=null);
	
	/**
	 * Créer une instance de Sql_Update permettant de faire une requête de type UPDATE
	 * 
	 * @param string $table  : [opt] La table sur laquelle agir
	 * @param array  $champs : [opt] Les données à modifier : array('champSql' => 'données');
	 * 
	 * @return Sql_Update : L'instance de l'objet Sql_Select créé
	 */
	public function update($table=null, $champs=null);
	
	/**
	 * Créer une instance de Sql_Delete permettant de faire une requête de type DELETE FROM
	 * 
	 * @param string $table : [opt] La table sur laquelle agir
	 * 
	 * @return Sql_Delete : L'instance de l'objet Sql_Select créé
	 */
	public function delete($table=null);
	
	/**
	 * Trouve le premier id libre pour une table et pour un champ
	 * 
	 * @param string $table : La table
	 * @param string $champ : Le champ
	 * 
	 * @return int/bool : L'id libre trouvé. False si erreur
	 */
	public function create_id($table, $champ);
	
	/**
	 * Execute la requête mise en paramètre
	 * Génère une exception s'il y a eu un échec
	 * 
	 * @param string $requete : La requête à exécuter
	 * 
	 * @return mixed : La ressource de la requête exécuté si elle a réussi, false sinon.
	 */
	public function query($requete);
}
?>