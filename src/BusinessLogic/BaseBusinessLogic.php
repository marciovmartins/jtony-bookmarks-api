<?php
namespace BusinessLogic;

use Silex\Application;

use Models;

class BaseBusinessLogic {

	protected $app;

	public function __construct(Application $app) {
		$this->app = $app;
	}

	protected function getRandomString($len=10) {
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%*-';
		$return = '';

		for ($n = 1; $n <= $len; $n++) {
			$rand = mt_rand(1, strlen($chars));
			$return .= $chars[$rand-1];
		}

		return $return;
	}

	protected function getStrongPass($weakPass) {
		$security = $this->app['settings']['security'];

		$weakPass = $weakPass.$security['hash_sufix'];

		$strongPass = crypt($weakPass, '$'.$security['crypt_method'].'$'.$security['crypt_cost'].'$'.$security['crypt_salt'].'$');

		return $strongPass;
	}	

}