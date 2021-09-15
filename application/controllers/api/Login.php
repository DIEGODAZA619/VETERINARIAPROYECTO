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

		$username = $this->post("username");
		$clave = $this->post("clave");
		$clavemd5 = md5($clave);

		$login = $this->usuarios_model->verificar_login($username,$clavemd5);
		if($login)
		{
			if($login[0]->estadouser == 'AC')
			{
				$date = new DateTime();
				$tokenData['idusuario'] = $login[0]->id_usuario;
				$tokenData['fecha'] = $date->getTimestamp();
				$jwtToken = $this->objOfJwt->GenerateToken($tokenData); //GENERA EL TOKEN

				$respuesta = array(
							'error' => true,
							'mensaje' => 'TOKEN',
							'fecha' => $date->getTimestamp(),
							'token'	  => $jwtToken
						);
				$this->response($respuesta, REST_Controller::HTTP_OK);	
			}
			elseif ($login[0]->estadouser == 'EX')
			{
				$respuesta = array(
							'error' => true,
							'mensaje' => 'ACTUALICE LA CONTRASEÑA',
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

}

?>