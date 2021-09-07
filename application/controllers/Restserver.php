<?php 
defined('BASEPATH') OR exit ('No direct script access allowed');

//use Restserver\Libraries\REST_Controller;
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/Format.php';

class Restserver extends REST_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		echo "HOLAA MUNDO";
	}
	public function saludo_get()
	{
		$data = array('HOLA','DATOS','CODEIGNITER');
		//echo "hola desde saludo";
		//echo json_encode($data);
		$data2 = array('uno' => 1,
					   'dos' => 2,
					   'tres' => 3,
					   'otros' => $data);
		$respuesta = array(
						'error' => false,
						'mensaje' => 'Correcto, informacion valida',
						'datos' => $data2
				);
		/*$respuesta = array(
						'error' => true,
						'mensaje' => 'Error, no se encontro la informacion valida',
						'datos' => null
				);*/

		//$this->response($respuesta, REST_Controller::HTTP_UNAUTHORIZED);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}
	function registro_post()
	{
		$data = $this->post();

		$nombre = $data['nombre'];
		$respuesta = array(
						'error' => false,
						'mensaje' => 'llega',
						'nombre' => $nombre,
						'datos' => $data
				      );
		$this->response($respuesta, REST_Controller::HTTP_OK);	
	}
	function datos_get()
	{
		$nombre = $this->uri->segment(3);//recupere el parametro 3 de la URL
		$nombre = $this->uri->segment(4);//recupere el parametro 3 de la URL
		$nombre = $this->uri->segment(5);//recupere el parametro 3 de la URL
		//$NOMBRE = "DIEGO";
		$respuesta = array(
							'error' => false,
							'mensaje' => 'llega',
							'nombre' => $nombre." respuesta"
						);
		$this->response($respuesta, REST_Controller::HTTP_OK);

	}

	function modificar_put()
	{
		//$nombre = $this->uri->segment(5);//recupere el parametro 3 de la URL
		$id = $this->uri->segment(3); //url
		$data = $this->put();         //put

		//PROCESAS INFORMACION
		$respuesta = array(
							'error' => false,
							'mensaje' => 'llega',
							'id' => $id,
							'datos' => $data
						);
		$this->response($respuesta, REST_Controller::HTTP_OK);

	}
}

?>
