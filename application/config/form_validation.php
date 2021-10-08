<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'usuarios_post' => array(
		array('field' => 'nrodocumento', 
			  'label' => 'nrodocumento',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[9]'),
		array('field' => 'nombres', 
			  'label' => 'nombres',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'primer_apellido', 
			  'label' => 'primer_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'segundo_apellido', 
			  'label' => 'segundo_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'tipo_usuario', 
			  'label' => 'tipo_usuario',
			  'rules' => 'trim|required|min_length[1]|max_length[2]|numeric|callback_verificarusuario_ckeck'),
		array('field' => 'clave', 
			  'label' => 'clave',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric'),
	),
	'usuarios_modificar_post' => array(
		array('field' => 'nrodocumento', 
			  'label' => 'nrodocumento',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[9]'),
		array('field' => 'nombres', 
			  'label' => 'nombres',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'primer_apellido', 
			  'label' => 'primer_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'segundo_apellido', 
			  'label' => 'segundo_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'idpersona', 
			  'label' => 'idpersona',
			  'rules' => 'trim|required|numeric')		
	)
	,
	'cambiarclave_post' => array(
		array('field' => 'claveactual', 
			  'label' => 'claveactual',
			  'rules' => 'trim|required|min_length[6]|max_length[20]|alpha_numeric'),
		array('field' => 'clavenueva', 
			  'label' => 'clavenueva',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric'),
		array('field' => 'confirmacion', 
			  'label' => 'confirmacion',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric')
	),

	'propietarios_post' => array(
		array('field' => 'documento', 
			  'label' => 'documento',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[9]'),
		array('field' => 'nombres', 
			  'label' => 'nombres',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'telefono', 
			  'label' => 'telefono',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[8]'),
		array('field' => 'direccion', 
			  'label' => 'direccion',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck')
	)



	

);



?>