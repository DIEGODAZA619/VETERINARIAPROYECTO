<?php 

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Login extends REST_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
		$this->load->model('usuarios_model');
	}

	public function index_post()
	{
		$data = $this->post();

		if(array_key_exists('username', $data) && array_key_exists('clave', $data))			
		{	
			$username = $this->post("username");
			$clave = $this->post("clave");
			$clavemd5 = md5($clave);
			$login = $this->usuarios_model->verificar_login($username,$clavemd5);//LLAMAR A UN MODELO
			if($login)
			{
				if($login[0]->estadouser == 'AC')
				{
					$date = new DateTime();
					//VALORES PARA EL TOKEN
					$tokenData['idusuario'] = $login[0]->id_usuario;
					$tokenData['fecha'] = $date->getTimestamp();

					$jwtToken = $this->objOfJwt->GenerateToken($tokenData); //GENERA EL TOKEN

					$respuesta = array(
								'error' => true,
								'mensaje' => 'TOKEN',
								'fecha' => $date->getTimestamp(),
								'token'	  => $jwtToken    //devolvemos el token
							);
					$this->response($respuesta, REST_Controller::HTTP_OK);	
				}
				elseif ($login[0]->estadouser == 'EX')
				{
					$date = new DateTime();
					//VALORES PARA EL TOKEN
					$tokenData['idusuario'] = $login[0]->id_usuario;
					$tokenData['fecha'] = $date->getTimestamp();
					$jwtToken = $this->objOfJwt->GenerateToken($tokenData); //GENERA EL TOKEN

					$respuesta = array(
								'error' => true,
								'mensaje' => 'ACTUALICE LA CONTRASEÑA',
								'fecha' => $date->getTimestamp(),
								'token_actualizacion'	=> $jwtToken    //devolvemos el token
								//'login'	  => $login
							);
					$this->response($respuesta, REST_Controller::HTTP_OK);	
				}
				elseif ($login[0]->estadouser == 'BA')
				{
					$respuesta = array(
								'error' => true,
								'mensaje' => 'USUARIO DESAHABILITADO',
								//'login'	  => $login
							);
					$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);	
				}
			}
			else
			{
				$respuesta = array(
								'error' => true,
								'mensaje' => 'Datos no Existentes',							
							);
				$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
			}
		}
		else
		{
			$respuesta = array(
								'error' => true,
								'mensaje' => 'Debe introducir los parametros correctos',						
							);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
	}

}

?>