<?php
namespace Models;

use Silex\Application;

class AdminModel extends BaseModel{
	
	public function __construct(Application $app) {
		parent::__construct($app, 'admins');
	}

}