<?php
class Correo{
	private $host;
	private $port;
	private $user;
	private $pass;
	private $nom;
	private $cor;
	
	/**
	 * constructor
	 */
	function Correo($host="xx.xx.xx.xx", $nom="juan", $pass="xxx", $port='25', $user="ecu911\leonju", $cor ="juan.leon@ecu911.gob.ec"){
		$this->setHost($host);
		$this->setNom($nom);
		$this->setPass($pass);
		$this->setPort($port);
		$this->setUser($user);
		$this->setCorreo($cor);
	}
	/**
	 * retorna el host
	 * @return string
	 */
	function getHost(){
		return $this->host;
	}
	
	/**
	 * iniciar el host
	 * @param string $host
	 */
	function setHost($host){
		$this->host = $host;
	}
	/**
	 * retorno el puerto
	 * @return string
	 */
	function getPort(){
		return $this->port;
	}
	/**
	 * inicia el puerto
	 * @param string $port
	 */
	function setPort($port){
		$this->port = $port;
	}
	/**
	 * retorno el usuario
	 * @return string
	 */
	function getUser(){
		return $this->user;
	}
	/**
	 * retorna el usuario
	 * @param string $user
	 */
	function setUser($user){
		$this->user = $user;
	}
	/**
	 * retorna contraseña
	 * @return string
	 */
	function getPass(){
		return $this->pass;
	}
	/**
	 * inicia contraseña
	 * @param unknown $pass
	 */
	function setPass($pass){
		$this->pass=$pass;
	}
	/**
	 * retorna nombre a mostrar
	 * @return string
	 */
	function getNom(){
		return $this->nom;
	}
	/**
	 * inicia el nombre
	 * @param unknown $nom
	 */
	function setNom($nom){
		$this->nom = $nom;
	}
	/**
	 * inica el nombre del buzon
	 */
	function setCorreo($cor){
		$this->cor = $cor;
	}
	/**
	 * obtiene nombre del correo
	 * @return string
	 */
	function  getCorreo(){
		return $this->cor;
	}
}
