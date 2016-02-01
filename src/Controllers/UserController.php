<?php
namespace Controllers;

use Silex\Application;

use Symfony\Component\HttpFoundation\Response;

use BusinessLogic\UserLogic;
use BusinessLogic\DataTransferObject\UserTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

class UserController extends BaseController{

	public function authenticate(Application $app) {
		$userDTO = new UserTransferObject($app);
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		$errorResponseDTO = $userDTO->validate('authenticate', $valuesPost);
		if($errorResponseDTO->getStatuscode()==Response::HTTP_BAD_REQUEST) {
			//houve erro na validaçao, retorna HTTP_BAD_REQUEST 
			$responseDTO = $errorResponseDTO;
		} else {
			//validou userDTO com OK, segue dados validados para autenticaçao
			$userLogic = new UserLogic($app);
			$responseDTO = $userLogic->authenticate($userDTO);
		}

		return $this->serviceResponse($responseDTO);
	}

	public function create(Application $app) {
		$userDTO = new UserTransferObject($app);
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		$errorResponseDTO = $userDTO->validate('create', $valuesPost);
		if($errorResponseDTO->getStatuscode()==Response::HTTP_BAD_REQUEST) {
			//houve erro na validaçao, retorna HTTP_BAD_REQUEST 
			$responseDTO = $errorResponseDTO;
		} else {
			//validou userDTO com OK, segue dados validados para autenticaçao
			$userLogic = new UserLogic($app);
			$responseDTO = $userLogic->create($userDTO);
		}

		return $this->serviceResponse($responseDTO);
	}
}
