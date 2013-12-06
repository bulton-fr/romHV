<?php
class Config extends \BFW_Sql\Classes\Modeles
{
	protected $_name = 'config';
	
	public function recupAll()
	{
		$req = $this->select()->from($this->_name);
		$res = $req->fetchAll();
		
		if($res) {return $res;}
		else {return array();}
	}
	
	public function update($ref, $value)
	{
		$this->update($this->_name, array('ref' => $ref, 'value' => $value));
	}
	
	public function getConfig($ref)
	{
		$req = $this->select()->from($this->_name)->where('ref="'.$ref.'"');
		$res = $req->fetch();
		
		if($res) {return $res['value'];}
		else {new Exception('Erreur dans la récupération de la config '.$ref);}
	}
}
?>