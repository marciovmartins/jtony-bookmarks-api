<?php
namespace BusinessLogic;

use BusinessLogic\DataTransferObject\UserTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\UserModel;

class UserLogic extends BaseBusinessLogic {

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate(UserTransferObject $userDTO) {
		$userModel = new UserModel($this->app);
		$responseDTO = new ResponseTransferObject($this->app);
		$foundUser = false;

		$passPrefix = $userModel->find(array(
					'email'=>$userDTO->getEmail(),
					'active'=>1
				),
				array('pass_prefix'));


		if(count($passPrefix) > 0 && isset($passPrefix[0]['pass_prefix'])) {
			$passPrefix = $passPrefix[0]['pass_prefix'];
		} else {
			$passPrefix = null;
		}

		if($passPrefix) {
			$userDTO->setPassword($passPrefix.$userDTO->getPassword());
			
			$rs = $userModel->find(array(
						'email'=>$userDTO->getEmail(),
						'password'=>$this->getStrongPass($userDTO->getPassword()),
						'active'=>1
					),
					array('id', 'name', 'email', 'nick'));

			if(count($rs) > 0){
				$rs = $rs[0];

				$tokenLogic = new TokenLogic($this->app);
				$token = $tokenLogic->create($rs['id'], TokenLogic::TOKEN_TYPE_USER);

				$userDTO->setId($rs['id']);
				$userDTO->setName($rs['name']);
				$userDTO->setNick($rs['nick']);

				$responseDTO->setStatuscode(Response::HTTP_OK);
				$responseDTO->setResource($userDTO);
				$responseDTO->setToken($token);
				$responseDTO->setMessage("User ".$userDTO->getName()." loged in");

				$foundUser = true;
			}
		}

		if(!$foundUser) {
			$responseDTO->setStatuscode(Response::HTTP_NOT_FOUND);
			$responseDTO->setResource(new UserTransferObject($this->app));
			$responseDTO->setToken(null);
			$responseDTO->setMessage('User not found');
		}

		return $responseDTO;
	}

	public function create(UserTransferObject $userDTO) {
		$userModel = new UserModel($this->app);

		$responseDTO = new ResponseTransferObject($this->app);
		$responseDTO->setResource(new UserTransferObject($this->app));
		$responseDTO->setToken(null);

		$rs = $userModel->find(array(
					'email'=>$userDTO->getEmail()
				), array('id'));

		if(count($rs) == 0) {
			$passPrefix = $this->getRandomString();

			$saved = $userModel->save(array(
						'name' => $userDTO->getName(),
						'email'=>$userDTO->getEmail(),
						'nick' => $userDTO->getNick(),
						'pass_prefix'=>$passPrefix,
						'password'=>$this->getStrongPass($passPrefix.$userDTO->getPassword())
					));
			
			if($saved) {
				return $this->authenticate($userDTO);
			} else {
				$responseDTO->setStatuscode(Response::HTTP_INTERNAL_SERVER_ERROR);
				$responseDTO->setMessage('User not Created, internal error has ocourred');
			}
		} else {
			$responseDTO->setStatuscode(Response::HTTP_CONFLICT);
			$responseDTO->setMessage('User with email: '.$userDTO->getEmail().' already exists');
		}

		return $responseDTO;
	}

}
