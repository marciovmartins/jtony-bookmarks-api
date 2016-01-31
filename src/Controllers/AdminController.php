<?php
namespace Controllers;

use BusinessLogic\AdminLogic;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class AdminController {

	public function authenticate(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$adminLogic = new AdminLogic($app);
		$data = $adminLogic->authenticate($valuesPost);

		return new Response(json_encode(array('message'=>$data['message'], 'resource'=>$data['resource'])), $data['statuscode'], array('x-access-token'=>$data['token']));
	}

}