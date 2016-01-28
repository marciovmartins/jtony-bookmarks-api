<?php
namespace BusinessLogic;

use Silex\Application;
use Models\AdminModel;

class AdminLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate($valuesPost) {
		$adminModel = new AdminModel($this->app);
		$return = [];

		//TODO: VERIFICAR ADMIN NO TOKEN/REDIS

		$rs = $adminModel->find(array(
					'email'=>$valuesPost['email'],
					'password'=>md5($valuesPost['password'].$this->app['SECURITY_HASH']),
					'active'=>1
				),
				array('id', 'name', 'email', 'nick'));

		if(count($rs) > 0){
			$rs = $rs[0];
			$return['statuscode'] = 200;
			$return['message'] = "Admin ".$rs['nick']." loged in";
			$return['resource'] = array(
					'id' => $rs['id'],
					'name' => $rs['name'],
					'email' => $rs['email'],
					'nick' => $rs['nick']
				);
		} else {
			$return['statuscode'] = 404;
			$return['message'] = "Admin not exists in database";
			$return['resource'] = null;
		}

		return $return;
	}

}