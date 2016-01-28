<?php
namespace Models;

use Silex\Application;

class UserModel extends BaseModel{
	
	public function __construct(Application $app) {
		parent::__construct($app, 'users');
	}

}