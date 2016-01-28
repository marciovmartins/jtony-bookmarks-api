<?php
namespace BusinessLogic;

use Silex\Application;
use Models\BookmarkModel;

class BookmarkLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function create($valuesPost) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		//TODO: VERIFICAR USER NO TOKEN/REDIS

		$rs = $bookmarkModel->find(array(
					'url'=>$valuesPost['url'],
					'id_user'=>$valuesPost['id_user'],
					'active'=>1
				));

		if(count($rs) == 0) {
			$saved = $bookmarkModel->save(array(
						'url'=>$valuesPost['url'],
						'id_user'=>$valuesPost['id_user']
					));
			
			if($saved){
				$return['statuscode'] = 200;
				$return['message'] = "Url saved";
				$return['resource'] = $valuesPost['url'];
			} else {
				$return['statuscode'] = 500;
				$return['message'] = "Url not Created, internal error has ocourred";
				$return['resource'] = null;
			}
		} else {
			$return['statuscode'] = 409;
			$return['message'] = "Url: ".$valuesPost['url']." already exists";
			$return['resource'] = null;
		}

		return $return;
	}

	public function update($valuesPost) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		//TODO: VERIFICAR USER NO TOKEN/REDIS

		$saved = $bookmarkModel->update(array(
					'url'=>$valuesPost['url'],
					'id_user'=>$valuesPost['id_user'],
					'active'=>$valuesPost['active']
				),
				array('id'=>$valuesPost['id']));
		
		if($saved){
			$return['statuscode'] = 200;
			$return['message'] = "Url updated";
			$return['resource'] = $valuesPost['url'];
		} else {
			$return['statuscode'] = 500;
			$return['message'] = "Url not updated, internal error has ocourred";
			$return['resource'] = null;
		}

		return $return;
	}	

	public function bookmarkList($valuesPost) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		//TODO: VERIFICAR USER NO TOKEN/REDIS

		$rs = $bookmarkModel->find(array(
					'id_user'=>$valuesPost['id_user'],
					'active'=>1
				),
				array('id', 'url'));

		$bookmarks = array_map(function($t){
			return array('id'=>$t['id'], 'url'=>$t['url']);
		}, $rs);		

		$return['statuscode'] = 200;
		$return['message'] = "teste";
		$return['resource'] = $bookmarks;

		return $return;
	}

}