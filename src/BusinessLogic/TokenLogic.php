<?php

namespace BusinessLogic;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Predis\Client;

use BusinessLogic\DataTransferObject\BaseTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

class TokenLogic extends BaseBusinessLogic {

	const TOKEN_TYPE_ADMIN = 1;
	const TOKEN_TYPE_USER = 2;

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	private function conn(){
		$redisSettings = $this->app['settings']['connRedis'];

		$redis = new \Predis\Client(array(
			"scheme" => $redisSettings["scheme"],
			"host" => $redisSettings["host"],
			"port" => $redisSettings["port"],
			"password" => $redisSettings["password"]));

		return $redis;
	}

	public function create($id, $type) {
		$redis = $this->conn();
		$token = md5($id.time().$type);
		$redis->set($token, json_encode(array('id'=>$id, 'type'=>$type)));
		$redis->expire($token, $this->app['settings']['security']['token_timeout']);

		return $token;
	}

	//TODO: CONVERTER PARA MIDDLEWARE BEFORE NO FUTURO
	public function authenticate($token, $id, $typeRestrict=null) {
		$responseDTO = new ResponseTransferObject($this->app);
		$responseDTO->setResource(new BaseTransferObject($this->app));

		$unauthorized = true;

		$redis = $this->conn();
		$valuesRedis = $redis->get($token);

		if($valuesRedis){
			$valuesRedis = json_decode($valuesRedis);
			
			//If token belongs for request user 
			//|| access is not restric for type user and token belongs to admin
			//|| access is restrict for escpecific type (ADMIN or USER in typeStrict parameter)
			if(($valuesRedis->id==$id && $valuesRedis->type==TokenLogic::TOKEN_TYPE_USER)
			||(is_null($typeRestrict) && $valuesRedis->type==TokenLogic::TOKEN_TYPE_ADMIN)	
			||(!is_null($typeRestrict) && $valuesRedis->type==$typeRestrict)) {
				$redis->expire($token, $this->app['settings']['security']['token_timeout']);

				$responseDTO->setStatuscode(Response::HTTP_OK);
				$responseDTO->setMessage("Valid Token");

				$responseDTO->setToken($token);
				$responseDTO->setTokenIdUser($valuesRedis->id);
				$responseDTO->setTokenTypeUser($valuesRedis->type);

				$unauthorized = false;
			} else {
				$unauthorized = true;
			}
		} else {
			$unauthorized = true;
		}

		if($unauthorized) {
			$responseDTO->setStatuscode(Response::HTTP_UNAUTHORIZED);
			$responseDTO->setMessage("unauthorized or expires token");
			$responseDTO->setToken(null);
		}

		return $responseDTO;
	}	

}
