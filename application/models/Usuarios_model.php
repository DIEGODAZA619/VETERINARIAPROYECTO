<?php

class Usuarios_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db_proyecto = $this->load->database('proyecto',TRUE);
	}

	function getusarios()
	{
		$query = $this->db_proyecto->query("select *
			                                  from personas p, usuarios u 
											 where p.id = u.id_persona");
		return $query->result();
	}

}




?>