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
							'errores' => $this->form_validation->get_errores_arreglo(),							
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
  			'estado' => 'AC',
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
}

?>