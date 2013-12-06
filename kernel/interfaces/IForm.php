<?php

namespace BFW\IKernel;

interface IForm
{
	/**
	 * Constructeur
	 * @param string $idForm : L'id du formulaire
	 */
	public function __construct($idForm=null);
	
	/**
	 * Accesseur set sur id_form
	 * @param string $idForm : L'id du formulaire
	 */
	public function set_idForm($idForm);
	
	/**
	 * Permet de créer un token pour le formulaire
	 * @return string : Le token à mettre dans un champ input de type hidden.
	 */
	public function create_token();
	
	/**
	 * Permet de vérifier si le token est correct
	 * @return bool : True si le toke est bon, false sinon.
	 */
	public function verif_token();
}
?>