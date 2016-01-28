<?php
namespace Models;

use Silex\Application;

class BookmarkModel extends BaseModel{
	
	public function __construct(Application $app) {
		parent::__construct($app, 'bookmarks');
	}

}