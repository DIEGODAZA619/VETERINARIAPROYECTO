<?php

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

//header('content-type: application/json; charset=utf-8');

class Usuarios extends REST_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
		$this->load->model('usuarios_model');
	}

  	public function getlistausuarios_get()
  	{
	  	try
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
			catch(Exception $e)
			{
				http_response_code('401');
				$respuesta = array(
												'error' => true,
												'mensaje' => 'ACCESO DENEGADOSSS',
												'datos' => $received_Token,
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
	public function verificarusuario_ckeck($tipo)
	{
		if($tipo == 1 || $tipo == 2)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('verificarusuario_ckeck', 'El campo {field} no es correcto');
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
		  			$this->load->library('form_validation');  //inicializando la libreria
		  			$this->form_validation->set_data($data);
		  			//$this->form_validation->set_rules('nombres','nombres','required');   // aplicando reglas de validacion
		  		//	if($this->form_validation->run() == FALSE) // obteniendo respuesta de validacion
		  			if($this->form_validation->run('usuarios_post'))
		  			{
		  				$respuesta = $this->registrarusuario($data);
		  			}
		  			else
		  			{	  				
		  				$respuesta = array(
								'error' => false,
								'mensaje' => 'datos incorrectos',
								'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
							);
		  			}

		  			

		  			//$respuesta = $this->registrarusuario($data);
		  			
		  			
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
												'error' => true,
												'mensaje' => 'ACCESO DENEGADO',
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
			}
  	}
  	function registrarusuario($data)
  	{
  		$nro_documento = trim($data['nrodocumento']);
  		$nombres = trim(strtoupper($data['nombres']));
  		$primer_apellido = trim(strtoupper($data['primer_apellido']));
  		$segundo_apellido = trim(strtoupper($data['segundo_apellido']));
  		$tipo_usuario = $data['tipo_usuario'];
  		$clave = $data['clave'];

  		$nombres_user = str_replace(" ","", $nombres);
  		$primer_apellido_user = str_replace(" ","",$primer_apellido);
  		$username = $nombres_user.".".$primer_apellido_user;
  		$clavemd5 = md5($clave);

  		$datap = array(
  			'numero_doc' => $nro_documento,
  			'nombres' => $nombres,
  			'primer_apellido' => $primer_apellido,
  			'segundo_apellido' => $segundo_apellido,
  			'estado' => 'AC',
  		);
  		$idpersona = $this->usuarios_model->guardarPersona($datap);

  		$datau = array(
  			'id_persona' => $idpersona,
  			'tipo_usuario' => $tipo_usuario,
  			'username' => $username,
  			'clave' => $clavemd5,
  			'estado' => 'EX',
  		);

  		$idusuario = $this->usuarios_model->guardarUsuario($datau);


  		$respuesta = array(
							'error' => false,
							'mensaje' => 'GUARDADO CORRECTAMENTE',
							'idpersona' => $idpersona,				
							'idusuario' => $idusuario,				
			);
			return $respuesta;
  	}
  	public function modificar_post()
  	{
  		try
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
		  					&& array_key_exists('idpersona', $data))) //idpersona del registro a modificar
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
		  			//$this->form_validation->set_rules('nombres','nombres','required');   // aplicando reglas de validacion
		  		//	if($this->form_validation->run() == FALSE) // obteniendo respuesta de validacion
		  			if($this->form_validation->run('usuarios_modificar_post'))
		  			{
		  				$respuesta = $this->modificarpersona($data);
		  				
		  			}
		  			else
		  			{	  				
		  				$respuesta = array(
								'error' => false,
								'mensaje' => 'datos incorrectos',
								'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
							);
		  			}
		  			//$respuesta = $this->registrarusuario($data);
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
												'error' => true,
												'mensaje' => 'ACCESO DENEGADO',
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
			}
  	}
  	function modificarpersona($data)
  	{
  		$idpersona = trim($data['idpersona']);
  		if($this->usuarios_model->getpersonaid($idpersona))
  		{
  			$nro_documento = trim($data['nrodocumento']);
	  		$nombres = trim(strtoupper($data['nombres']));
	  		$primer_apellido = trim(strtoupper($data['primer_apellido']));
	  		$segundo_apellido = trim(strtoupper($data['segundo_apellido']));

  			$datap = array(
	  			'numero_doc' => $nro_documento,
	  			'nombres' => $nombres,
	  			'primer_apellido' => $primer_apellido,
	  			'segundo_apellido' => $segundo_apellido,	  			
	  		);
  			$idpersonaupdate = $this->usuarios_model->updatePersona($idpersona,$datap);
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'DATOS ACTUALIZADOS CORRECTAMENTE',
							'idpersona' => $idpersona
				);
  		}	
  		else
  		{
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, El id de persona no se encuentra registrado',
				);	
  		}
  		
  		return $respuesta;
  	}

  	function cambiarclave_post()
  	{
  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
  		{
	  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
	  		$iduser = $jwtData['idusuario']; // OBTENER VALORES DEL TOKEN
	  		
	  		$data = $this->post();
	  		if (!(array_key_exists('claveactual', $data)
	  					&& array_key_exists('clavenueva', $data)
	  					&& array_key_exists('confirmacion', $data))) //idpersona del registro a modificar
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
	  			//$this->form_validation->set_rules('nombres','nombres','required');   // aplicando reglas de validacion
	  		//	if($this->form_validation->run() == FALSE) // obteniendo respuesta de validacion
	  			if($this->form_validation->run('cambiarclave_post'))
	  			{
	  				$respuesta = $this->actualizarclaveusuario($iduser,$data);
	  			}
	  			else
	  			{	  				
	  				$respuesta = array(
							'error' => false,
							'mensaje' => 'datos incorrectos',
							'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
						);
	  			}
	  			//$respuesta = $this->registrarusuario($data);
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
  	function actualizarclaveusuario($idusuario,$data)
  	{
  		$claveactual = md5($data['claveactual']);
  		$clavenueva = md5($data['clavenueva']);
  		$confirmacion = md5($data['confirmacion']);

  		if($clavenueva == $confirmacion)
  		{
  			if($clavenueva != $claveactual)
  			{
  				if($this->usuarios_model->getverificarclaveusario($idusuario,$claveactual))
	  			{
	  					$datau = array(
				  			'clave' => $clavenueva,
				  			'estado' => 'AC',
				  		);
  						$idusuario = $this->usuarios_model->updateUsuario($idusuario,$datau);

  						$respuesta = array(
								'error' => false,
								'mensaje' => 'CLAVE ACTUALIZADA CORRECTAMENTE, INICIE SESSION NUEVAMENTE CON LAS NUEVAS CREDENCIALES',
								'estado' => 'AC',
								'idusuario' => $idusuario,
							);
	  			}
	  			else
	  			{
	  					$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, la contraseña actual no es correcta',
							);
	  			}
  			}
  			else
  			{
  				$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, la contraseña nueva deber ser diferente a la actual',
					);
  			}
  		}
  		else
  		{
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, No coinciden la nueva contraseña con la confirmacion',
					);
  		}

  		return $respuesta;			
  	}
  	public function getlistausuarios2_get()
  	{
	  	try
	  	{
  			$data = $this->usuarios_model->getusarios();
	  		if($data)
	  		{
	  			$respuesta = array(
							'error' => false,
							'mensaje' => 'Correcto, datos usuario',
							'datos' => $data,							
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
		catch(Exception $e)
		{
			http_response_code('401');
			$respuesta = array(
							'error' => true,
							'mensaje' => 'ACCESO DENEGADO',
							"status" =>false,
							"message" => $e->getMessage()
			              	);
			echo json_encode($respuesta);
			exit;
		}
  	}
}

?>