<?php

require APPPATH . '/libraries/REST_Controller.php';

class Usuarios extends REST_Controller {
  public function __construct(){
		parent::__construct();
		$this->load->model('usuarios_model');
  }

  	public function getlistausuarios_get()
  	{
  		$data = $this->usuarios_model->getusarios();

  		$respuesta = array(
						'error' => false,
						'mensaje' => 'Correcto, datos usuario',
						'datos' => $data
				);
		  $this->response($respuesta, REST_Controller::HTTP_OK);
  	}
}

?>