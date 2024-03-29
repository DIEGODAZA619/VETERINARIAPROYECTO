<?php

class Usuarios_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db_proyecto = $this->load->database('proyecto',TRUE);
	}

	
	function verificar_login($username, $clave)
	{
		$query = $this->db_proyecto->query("select u.estado as estadouser, 
												   u.id as id_usuario, 		
											       p.*,
											       u.*
			                                  from personas p, usuarios u 
											 where p.id = u.id_persona
											   and u.username = '".$username."' 
											   and u.clave = '".$clave."'"
											);
		return $query->result();
	}

	function getusarios()
	{
		$query = $this->db_proyecto->query("select *
			                                  from personas p, usuarios u 
											 where p.id = u.id_persona");
		return $query->result();
	}


	function getusarios_pruebas()
	{
		$query = $this->db_proyecto->query("select u.id, p.nombres as message, u.estado as state, u.tipo_usuario as fechaRegistro, u.username as fechaRecepcion
			                                  from personas p, usuarios u 
											 where p.id = u.id_persona
											 order by u.id desc");
		return $query->result();
	}
	function guardarMensaje($data)
	{
		$this->db_proyecto->insert('message',$data);
		return $this->db_proyecto->insert_id();
	}
	function getMensajes()
	{
		$query = $this->db_proyecto->query("select *
			                                  from message");
		return $query->result();
	}

	function guardarPersona($data)
	{
		$this->db_proyecto->insert('personas',$data);
		return $this->db_proyecto->insert_id();
	}
	function updatePersona($idpersona,$data)
	{
		$this->db_proyecto->where('id', $idpersona);
		return $this->db_proyecto->update('personas',$data);
	}
	function guardarUsuario($data)
	{
		$this->db_proyecto->insert('usuarios',$data);
		return $this->db_proyecto->insert_id();
	}
	function getpersonaid($idpersona)
	{
		$query = $this->db_proyecto->query("select *
			                                  from personas p
											 where p.id = ".$idpersona);
		return $query->result();
	}
	function getverificarclaveusario($idusuario, $clave)
	{
		$query = $this->db_proyecto->query("select *
			                                  from usuarios u 
											 where u.id = '".$idusuario."' 
											   and u.clave = '".$clave."'"
											);
		return $query->result();
	}
	function updateUsuario($idusuario,$data)
	{
		$this->db_proyecto->where('id', $idusuario);
		return $this->db_proyecto->update('usuarios',$data);
	}

}




?>