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

	function guardarPersona($data)
	{
		$this->db_proyecto->insert('personas',$data);
		return $this->db_proyecto->insert_id();
	}
	function guardarUsuario($data)
	{
		$this->db_proyecto->insert('usuarios',$data);
		return $this->db_proyecto->insert_id();
	}

}




?>