<?php
namespace Controllers;

use Silex\Application;

use Symfony\Component\HttpFoundation\Response;

use BusinessLogic\AdminLogic;
use BusinessLogic\UserLogic;
use BusinessLogic\DataTransferObject\AdminTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

class AdminController extends BaseController{

	public function authenticate(Application $app) {
		$adminDTO = new AdminTransferObject($app);
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		$errorResponseDTO = $adminDTO->validate('authenticate', $valuesPost);
		if($errorResponseDTO->getStatuscode()==Response::HTTP_BAD_REQUEST) {
			//houve erro na validaçao, retorna HTTP_BAD_REQUEST 
			$responseDTO = $errorResponseDTO;
		} else {
			//validou userDTO com OK, segue dados validados para autenticaçao
			$adminLogic = new AdminLogic($app);
			$responseDTO = $adminLogic->authenticate($adminDTO);
		}

		return $this->serviceResponse($responseDTO);
	}	

	public function getUsers($idAdmin, Application $app) {

		$adminDTO = new AdminTransferObject($app);
		$req = $app['request'];
		$idAdmin = (int)$idAdmin;
		$adminDTO->setId($idAdmin);

		$userLogic = new UserLogic($app);
		$responseDTO = $userLogic->userList($adminDTO, $req->headers->get('x-access-token'));		

		return $this->serviceResponse($responseDTO);
	}


}