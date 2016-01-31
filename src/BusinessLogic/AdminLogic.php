<?php
namespace BusinessLogic;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\AdminModel;

class AdminLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function authenticate($valuesPost) {
		$adminModel = new AdminModel($this->app);
		$foundAdmin = false;	
		$return = [];

		$passPrefix = $adminModel->find(array(
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
			$rs = $adminModel->find(array(
						'email'=>$valuesPost['email'],
						'password'=>$this->getStrongPass($passPrefix.$valuesPost['password']),
						'active'=>1
					),
					array('id', 'name', 'email', 'nick'));

			if(count($rs) > 0){
				$rs = $rs[0];

				$tokenLogic = new TokenLogic($this->app);
				$token = $tokenLogic->create($rs['id'], TokenLogic::TOKEN_TYPE_ADMIN);

				$return['statuscode'] = Response::HTTP_OK;;
				$return['token'] = $token;
				$return['message'] = "Admin ".$rs['nick']." loged in";
				$return['resource'] = array(
						'id' => $rs['id'],
						'name' => $rs['name'],
						'email' => $rs['email'],
						'nick' => $rs['nick']
					);

				$foundAdmin = true;
			}
		}

		if(!$foundAdmin) {
			$return['statuscode'] = Response::HTTP_NOT_FOUND;
			$return['token'] = null;
			$return['message'] = "Admin not found";
			$return['resource'] = null;
		}

		return $return;
	}

}