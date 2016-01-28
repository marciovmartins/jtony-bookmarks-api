<?php
namespace BusinessLogic;

use Silex\Application;
use Models;

class BaseBusinessLogic {

	protected $app;

	public function __construct(Application $app) {
		$this->app = $app;
	}

}