<?php
namespace modules\users\modeles;

/**
 * Modèle pour la table users
 * 
 * @author Maxime Vermeulen <bulton.fr@gmail.com>
 */
class UsersGroupe extends \BFW_Sql\Classes\Modeles
{
    /**
     * Nom de la table
     */
    protected $_name = 'users_grp';
    
    /**
     * récupère tous les groupes d'un utilisateur
     * 
     * @param int $idUser : l'id de l'utilisateur
     * 
     * @return array
     */
    public function getGroupesForUser($idUser)
    {
        $default = array();
        if(!is_int($idUser))
        {
            if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
            else {return $default;}
        }
        
        $req = $this->select()->from($this->_name, 'idGroupe')->where('idUser=:idUser', array(':idUser' => $idUser));
        $res = $req->fetchAll();
        
        if($res) {return $res;}
        else {return $default;}
    }
    
    /**
     * Récupère les utilisateurs d'un groupe
     * 
     * @param int $idGroupe : L'id du groupe
     */
    public function getUsersForGroupe($idGroupe)
    {
        $default = array();
        if(!is_int($idGroupe))
        {
            if($this->get_debug()) {throw new Exception('L\'id donné en paramètre doit être de type int.');}
            else {return $default;}
        }
        
        $req = $this->select()->from($this->_name, 'idUser')->where('idGroupe=:idGroupe', array(':idGroupe' => $idGroupe));
        $res = $req->fetchAll();
        
        if($res) {return $res;}
        else {return $default;}
    }
    
    /**
     * Récupère les autres utilisateurs des groupes aux-quelles appartiennent un user
     * 
     * @param int    $idUser : L'id de l'utilisateur
     * @param bool   $forReq : Indique si c'est pour une requête sql ou non
     * @param string $as     : le as à utiliser si on doit générer la requête sql en sortie
     * 
     * @return array/string
     */
    public function getUsersForUser($idUser, $forReq=false, $as='')
    {
        //Tous les utilisateurs appartenant à tous les groupes de notre user
        
        if($forReq) {$default = '';}
        else {$default = array();}
        
        if(!is_int($idUser) || !is_bool($forReq))
        {
            if($this->get_debug()) {throw new Exception('Les données en paramètre ne sont pas correctes.');}
            else {return $default;}
        }
        
        $req = $this->select()
                    ->from(array('ug1' => $this->_name), '')
                    ->joinLeft(array('ug2' => $this->_name), 'ug1.idGroupe=ug2.idGroupe', array('noAS_noEntoure' => 'DISTINCT ug2.idUser'))
                    ->where('ug1.idUser=:idUser', array(':idUser' => $idUser));
        $res = $req->fetchAll();
        
        if($res)
        {
            if($forReq)
            {
                if($as != '') {$as = $as.'.';}
                
                $return = '';
                foreach($res as $val)
                {
                    if($return != '') {$return .= ' OR ';}
                    $return .= $as.'idUser='.$val['idUser'];
                }
                
                return $return;
            }
            else {return $res;}
        }
        else {return $default;}
    }
}