<?php
namespace Controllers;

use BusinessLogic\UserLogic;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class UserController {

	public function authenticate(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$userLogic = new UserLogic($app);
		$data = $userLogic->authenticate($valuesPost);

		return new Response(json_encode($data), $data['statuscode']);
	}

	public function create(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$userLogic = new UserLogic($app);
		$data = $userLogic->create($valuesPost);

		return new Response(json_encode($data), $data['statuscode']);
	}	

}