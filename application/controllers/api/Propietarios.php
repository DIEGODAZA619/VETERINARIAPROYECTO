<?php

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Propietarios extends REST_Controller 
{
  public function __construct(){
		parent::__construct();
		$this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
		$this->load->model('usuarios_model');
		$this->load->model('propietarios_model');
  }

  public function getlistapropietarios_get()
  	{
	  	try
	  	{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$iduser = $jwtData['idusuario']; // OBTENER VALORES DEL TOKEN
		  		$data = $this->propietarios_model->getPropietarios(); //---
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
								'mensaje' => 'No se recupero ningun registro de propietario',
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
			catch(Exception $e)
			{
				http_response_code('401');
				$respuesta = array(
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
			}
  	}
  	public function verificarcadena_ckeck($cadena)
		{

			$patron =  "/^[a-zA-Z\sñáéíóúÁÉÍÓÚ]+$/";
			if(preg_match($patron, $cadena))
			{
				return true;				
			}
			else
			{
				$this->form_validation->set_message('verificarcadena_ckeck', 'El campo {field} solo debe contener letras');
				return false;
			}
		}
  	function registrar_post()
  	{
  		try
  		{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$iduser = $jwtData['idusuario']; // OBTENER VALORES DEL TOKEN
		  		
		  		$data = $this->post();
		  		if (!(array_key_exists('documento', $data)
		  					&& array_key_exists('nombres', $data)
		  					&& array_key_exists('telefono', $data)
		  					&& array_key_exists('direccion', $data))
		  				)
		  		{
		  			$respuesta = array(
									'error' => true,
									'mensaje' => 'Debe introducir los parametros correctos',						
								);
						$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);	
		  		}
		  		else
		  		{	  			
		  			$this->load->library('form_validation');  //inicializando la libreria
		  			$this->form_validation->set_data($data);		  			
		  			if($this->form_validation->run('propietarios_post'))
		  			{
		  				$respuesta = $this->registrarpropietario($data);		  				
		  			}
		  			else
		  			{	  				
		  				$respuesta = array(
								'error' => false,
								'mensaje' => 'datos incorrectos',
								'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
							);
		  			}
		  			
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
			catch(Exception $e)
			{
				http_response_code('401');
				$respuesta = array(
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
			}
  	}
  	function registrarpropietario($data)
  	{
  		$documento = trim($data['documento']);
  		$nombres = trim(strtoupper($data['nombres']));
  		$telefono = trim($data['telefono']);
  		$direccion = trim(strtoupper($data['direccion']));
  		
  		$data = array(
  			'documento' => $documento,
  			'nombres' => $nombres,
  			'telefono' => $telefono,
  			'direccion' => $direccion,
  			'estado' => 'AC',
  		);
  		$idpropietario = $this->propietarios_model->guardarPropietario($data);
  		$respuesta = array(
							'error' => false,
							'mensaje' => 'GUARDADO CORRECTAMENTE',
							'idpersona' => $idpropietario,											
			);
			return $respuesta;
  	}

}
?>