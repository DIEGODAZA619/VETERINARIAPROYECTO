<?php

class Propietarios_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->db_proyecto = $this->load->database('proyecto',TRUE);
	}
	function getPropietarios()
	{
		$query = $this->db_proyecto->query("select *
			                                  from propietarios p
											 where estado = 'AC'");
		return $query->result();
	}
	function guardarPropietario($data)
	{
		$this->db_proyecto->insert('propietarios',$data);
		return $this->db_proyecto->insert_id();
	}


}
?>