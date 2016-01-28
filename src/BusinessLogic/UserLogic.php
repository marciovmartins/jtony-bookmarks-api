<?php
namespace BusinessLogic;

use Silex\Application;
use Models\UserModel;

class UserLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate($valuesPost) {
		$userModel = new UserModel($this->app);
		$return = [];

		//TODO: VERIFICAR USER NO TOKEN/REDIS

		$rs = $userModel->find(array(
					'email'=>$valuesPost['email'],
					'password'=>md5($valuesPost['password'].$this->app['SECURITY_HASH']),
					'active'=>1
				),
				array('id', 'name', 'email', 'nick'));

		if(count($rs) > 0){
			$rs = $rs[0];
			$return['statuscode'] = 200;
			$return['message'] = "User ".$rs['nick']." loged in";
			$return['resource'] = array(
					'id' => $rs['id'],
					'name' => $rs['name'],
					'email' => $rs['email'],
					'nick' => $rs['nick']
				);
		} else {
			$return['statuscode'] = 404;
			$return['message'] = "User not found";
			$return['resource'] = null;
		}

		return $return;
	}

	public function create($valuesPost) {
		$userModel = new UserModel($this->app);
		$return = [];

		//TODO: VERIFICAR USER NO TOKEN/REDIS

		$rs = $userModel->find(array(
					'email'=>$valuesPost['email'],
					'password'=>md5($valuesPost['password'].$this->app['SECURITY_HASH'])
				));

		if(count($rs) == 0){
			$saved = $userModel->save(array(
						'name' => $valuesPost['name'],
						'email'=>$valuesPost['email'],
						'nick' => $valuesPost['nick'],
						'password'=>md5($valuesPost['password'].$this->app['SECURITY_HASH'])
					));

			
			if($saved){
				$return = $this->authenticate($valuesPost);
			} else {
				$return['statuscode'] = 500;
				$return['message'] = "User not Created, internal error has ocourred";
				$return['resource'] = null;
			}
		} else {
			$return['statuscode'] = 409;
			$return['message'] = "User with email: ".$valuesPost['email']." already exists";
			$return['resource'] = null;
		}

		return $return;
	}	

}