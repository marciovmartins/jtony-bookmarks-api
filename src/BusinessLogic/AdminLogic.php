<?php
namespace BusinessLogic;

use BusinessLogic\DataTransferObject\AdminTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\AdminModel;

class AdminLogic extends BaseBusinessLogic {

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate(AdminTransferObject $adminDTO) {
		$adminModel = new AdminModel($this->app);
		$responseDTO = new ResponseTransferObject($this->app);
		$foundUser = false;

		$passPrefix = $adminModel->find(array(
					'email'=>$adminDTO->getEmail(),
					'active'=>1
				),
				array('pass_prefix'));


		if(count($passPrefix) > 0 && isset($passPrefix[0]['pass_prefix'])) {
			$passPrefix = $passPrefix[0]['pass_prefix'];
		} else {
			$passPrefix = null;
		}

		if($passPrefix) {
			$adminDTO->setPassword($passPrefix.$adminDTO->getPassword());
			
			$rs = $adminModel->find(array(
						'email'=>$adminDTO->getEmail(),
						'password'=>$this->getStrongPass($adminDTO->getPassword()),
						'active'=>1
					),
					array('id', 'name', 'email', 'nick'));

			if(count($rs) > 0){
				$rs = $rs[0];

				$tokenLogic = new TokenLogic($this->app);
				$token = $tokenLogic->create($rs['id'], TokenLogic::TOKEN_TYPE_ADMIN);

				$adminDTO->setId($rs['id']);
				$adminDTO->setName($rs['name']);
				$adminDTO->setNick($rs['nick']);

				$responseDTO->setStatuscode(Response::HTTP_OK);
				$responseDTO->setResource($adminDTO);
				$responseDTO->setToken($token);
				$responseDTO->setMessage("Admin ".$adminDTO->getName()." loged in");

				$foundUser = true;
			}
		}

		if(!$foundUser) {
			$responseDTO->setStatuscode(Response::HTTP_NOT_FOUND);
			$responseDTO->setResource(new AdminTransferObject($this->app));
			$responseDTO->setToken(null);
			$responseDTO->setMessage('Admin not found');
		}

		return $responseDTO;
	}
}