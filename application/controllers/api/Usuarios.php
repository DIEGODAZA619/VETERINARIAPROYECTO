<?php

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Usuarios extends REST_Controller {
  public function __construct(){
		parent::__construct();
		$this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
		$this->load->model('usuarios_model');
  }

  	public function getlistausuarios_get()
  	{
  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
  		{
	  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
	  		$iduser = $jwtData['idusuario']; // OBTENER VALORES DEL TOKEN
	  		$data = $this->usuarios_model->getusarios();
	  		if($data)
	  		{
	  			$respuesta = array(
							'error' => false,
							'mensaje' => 'Correcto, datos usuario',
							'datos' => $data,
							'token' => $jwtData,
							'iduser' => $iduser
					);
			  	$this->response($respuesta, REST_Controller::HTTP_OK);	
	  		}
	  		else
	  		{
	  			$respuesta = array(
							'error' => true,
							'mensaje' => 'No se recupero ningun registro de usuarios',
							'datos' => null,							
					);
			  	$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);	
	  		}	  		
		  }
		  else
		  {
		  	$respuesta = array(
							'error' => true,
							'mensaje' => 'ACCESO DENEGADO',
					);
			  $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		  }

  	}

  	function registrar_post()
  	{
  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
  		{
	  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
	  		$iduser = $jwtData['idusuario']; // OBTENER VALORES DEL TOKEN
	  		
	  		$data = $this->post();
	  		if (!(array_key_exists('nrodocumento', $data)
	  					&& array_key_exists('nombres', $data)
	  					&& array_key_exists('primer_apellido', $data)
	  					&& array_key_exists('segundo_apellido', $data)
	  					&& array_key_exists('tipo_usuario', $data)
	  					&& array_key_exists('clave', $data)))
	  		{
	  			$respuesta = array(
								'error' => true,
								'mensaje' => 'Debe introducir los parametros correctos',						
							);
					$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);	
	  		}
	  		else
	  		{

	  			//validacion de datos
	  			$respuesta = $this->registrarusuario($data);
	  			
	  			/*$respuesta = array(
							'error' => false,
							'mensaje' => 'pruebaaa',
							'datos' => $data,							
					);*/
			  	$this->response($respuesta, REST_Controller::HTTP_OK);	
	  		}
	  	}
		  else
		  {
		  	$respuesta = array(
							'error' => true,
							'mensaje' => 'ACCESO DENEGADO',
					);
			  $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		  }
  	}
  	function registrarusuario($data)
  	{
  		$respuesta = array(
							'error' => false,
							'mensaje' => 'pruebaaakjdkdjdj',
							'datos' => $data,							
			);
			return $respuesta;

  	}
}

?>