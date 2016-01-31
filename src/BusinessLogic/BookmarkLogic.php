<?php
namespace BusinessLogic;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\BookmarkModel;

class BookmarkLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function create($valuesPost, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $valuesPost['id_user'], TokenLogic::TOKEN_TYPE_USER);

		if(!is_null($tokenAuth->token)) {
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
					$return['statuscode'] = Response::HTTP_OK;
					$return['message'] = "Url saved";
					$return['resource'] = $valuesPost['url'];
				} else {
					$return['statuscode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
					$return['message'] = "Url not Created, internal error has ocourred";
					$return['resource'] = null;
				}
			} else {
				$return['statuscode'] = Response::HTTP_CONFLICT;
				$return['message'] = "Url: ".$valuesPost['url']." already exists";
				$return['resource'] = null;
			}
		} else {
			$return = $tokenAuth->response;
		}

		return $return;
	}

	public function update($valuesPost, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		$bookmarkId = $valuesPost['id'];
		unset($valuesPost['id']);

		//apenas o usuario que criou o bookmark pode alterar, 
		//remover esse tratamento para que admins possam editar bookmarks
		$rs = $bookmarkModel->find(
				array('id'=>$bookmarkId),
				array('id_user', 'active')
			);
		$rsBookmark = $rs[0];
		$valuesPost['id_user'] = $rsBookmark['id_user'];

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $valuesPost['id_user']);

		if(!$rsBookmark['active']) {
			$return['statuscode'] = Response::HTTP_NOT_FOUND;
			$return['token'] = null;
			$return['message'] = "Bookmark not found";
			$return['resource'] = null;
		} else if(!is_null($tokenAuth->token)) {
			$saved = $bookmarkModel->update(
					$valuesPost,
					array(
						'id'=>$bookmarkId
					));
			
			if($saved){
				$return['statuscode'] = Response::HTTP_OK;
				$return['message'] = isset($valuesPost['url']) ? "Bookmark updated" : "Bookmark removed";
				$return['resource'] = isset($valuesPost['url']) ? $valuesPost['url'] : null;
			} else {
				$return['statuscode'] = Response::HTTP_INTERNAL_SERVER_ERROR;
				$return['message'] = "Url not updated, internal error has ocourred";
				$return['resource'] = null;
			}
		} else {
			$return = $tokenAuth->response;
		}

		return $return;
	}	

	public function bookmarkList($idUser, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$return = [];

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $idUser);

		if(!is_null($tokenAuth->token)) {
			$rs = $bookmarkModel->find(array(
						'id_user'=>$idUser,
						'active'=>1
					),
					array('id', 'url'));

			$bookmarks = array_map(function($t){
				return array('id'=>$t['id'], 'url'=>$t['url']);
			}, $rs);		

			$return['statuscode'] = Response::HTTP_OK;
			$return['message'] = $bookmarks;
			$return['resource'] = $bookmarks;
		} else {
			$return = $tokenAuth->response;
		}

		return $return;
	}

}