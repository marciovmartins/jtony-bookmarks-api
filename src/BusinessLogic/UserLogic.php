<?php
namespace BusinessLogic;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\UserModel;

class UserLogic extends BaseBusinessLogic {

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate($valuesPost) {
		$userModel = new UserModel($this->app);		
		$foundUser = false;
		$return = [];

		$passPrefix = $userModel->find(array(
					'email'=>$valuesPost['email'],
					'active'=>1
				),
				array('pass_prefix'));

		if(count($passPrefix) > 0 && isset($passPrefix[0]['pass_prefix'])) {
			$passPrefix = $passPrefix[0]['pass_prefix'];
		} else {
			$passPrefix = null;
		}

		if($passPrefix) {
			$rs = $userModel->find(array(
						'email'=>$valuesPost['email'],
						'password'=>$this->getStrongPass($passPrefix.$valuesPost['password']),
						'active'=>1
					),
					array('id', 'name', 'email', 'nick'));

			if(count($rs) > 0){
				$rs = $rs[0];

				$tokenLogic = new TokenLogic($this->app);
				$token = $tokenLogic->create($rs['id'], TokenLogic::TOKEN_TYPE_USER);

				$return['statuscode'] = Response::HTTP_OK;
				$return['token'] = $token;
				$return['message'] = "User ".$rs['nick']." loged in";
				$return['resource'] = array(
						'id' => $rs['id'],
						'name' => $rs['name'],
						'email' => $rs['email'],
						'nick' => $rs['nick'],
					);

				$foundUser = true;
			}
		}

		if(!$foundUser) {
			$return['statuscode'] = Response::HTTP_NOT_FOUND;
			$return['token'] = null;
			$return['message'] = "User not found";
			$return['resource'] = null;
		}

		return $return;
	}

	public function create($valuesPost) {
		$userModel = new UserModel($this->app);
		$security = $this->app['settings']['security'];
		$return = [];

		$rs = $userModel->find(array(
					'email'=>$valuesPost['email']
				), array('id'));

		if(count($rs) == 0) {
			$passPrefix = $this->getRandomString();

			$saved = $userModel->save(array(
						'name' => $valuesPost['name'],
						'email'=>$valuesPost['email'],
						'nick' => $valuesPost['nick'],
						'pass_prefix'=>$passPrefix,
						'password'=>$this->getStrongPass($passPrefix.$valuesPost['password'])
					));
			
			if($saved) {
				$return = $this->authenticate($valuesPost);
			} else {
				$return['statuscode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
				$return['token'] = null;
				$return['message'] = "User not Created, internal error has ocourred";
				$return['resource'] = null;
			}
		} else {
			$return['statuscode'] = Response::HTTP_CONFLICT;
			$return['token'] = null;
			$return['message'] = "User with email: ".$valuesPost['email']." already exists";
			$return['resource'] = null;
		}

		return $return;
	}

}
