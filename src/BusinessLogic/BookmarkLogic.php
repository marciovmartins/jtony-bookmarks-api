<?php
namespace BusinessLogic;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

use Models\BookmarkModel;

use BusinessLogic\DataTransferObject\BookmarkTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;
use BusinessLogic\DataTransferObject\BaseTransferObject;

class BookmarkLogic extends BaseBusinessLogic{

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function create(BookmarkTransferObject $bookmarkDTO, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$responseDTO = new ResponseTransferObject($this->app);

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $bookmarkDTO->getIdUser(), TokenLogic::TOKEN_TYPE_USER);

		if($tokenAuth->getStatuscode()==Response::HTTP_OK) {
			$rs = $bookmarkModel->find(array(
						'url'=>$bookmarkDTO->getUrl(),
						'id_user'=>$bookmarkDTO->getIduser(),
						'active'=>1
					));

			if(count($rs) == 0) {
				$saved = $bookmarkModel->save(array(
							'url'=>$bookmarkDTO->getUrl(),
							'id_user'=>$bookmarkDTO->getIduser()
						));
				
				if($saved) {
					$responseDTO->setStatuscode(Response::HTTP_OK);
					$responseDTO->setMessage("Url saved");
					$responseDTO->setResource($bookmarkDTO);
				} else {
					$responseDTO->setStatuscode(Response::HTTP_INTERNAL_SERVER_ERROR);
					$responseDTO->setMessage("Url not Created, internal error has ocourred");
					$responseDTO->setResource(new BaseTransferObject($this->app));
				}
			} else {
				$responseDTO->setStatuscode(Response::HTTP_CONFLICT);
				$responseDTO->setMessage("Url: ".$bookmarkDTO->getUrl()." already exists");
				$responseDTO->setResource(new BaseTransferObject($this->app));
			}
		} else {
			$responseDTO = $tokenAuth;
		}

		return $responseDTO;
	}

	public function update(BookmarkTransferObject $bookmarkDTO, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$responseDTO = new ResponseTransferObject($this->app);

		//apenas o usuario que criou o bookmark pode alterar, 
		//remover esse tratamento para que admins possam editar bookmarks
		$rsBookmark = $bookmarkModel->find(
				array('id'=>$bookmarkDTO->getId()),
				array('id_user', 'url', 'active')
			);
		$rsBookmark = (count($rsBookmark)) ? $rsBookmark[0] : array('id_user'=>0, 'url'=>'', 'active'=>0);
		$bookmarkDTO->setIdUser($rsBookmark['id_user']);

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $bookmarkDTO->getIdUser());

		if(!$rsBookmark['active'] && $tokenAuth->getStatuscode()==Response::HTTP_OK) {
			$responseDTO->setStatuscode(Response::HTTP_NOT_FOUND);
			$responseDTO->setMessage("Bookmark not found");
			$responseDTO->setResource(new BaseTransferObject($this->app));

		} else if($bookmarkDTO->getUrl()!=null && $bookmarkDTO->getUrl()==$rsBookmark['url'] && $tokenAuth->getStatuscode()==Response::HTTP_OK) {
			$responseDTO->setStatuscode(Response::HTTP_CONFLICT);
			$responseDTO->setMessage("Url: ".$bookmarkDTO->getUrl()." is the same url in database");
			$responseDTO->setResource(new BaseTransferObject($this->app));

		} else if($tokenAuth->getStatuscode()==Response::HTTP_OK) {

			$updateFields = ($bookmarkDTO->getUrl()==null) ? array('active'=>0) : array('url'=>$bookmarkDTO->getUrl());

			$saved = $bookmarkModel->update(
					$updateFields,
					array(
						'id'=>$bookmarkDTO->getId()
					));
			
			if($saved){
				$message = ($bookmarkDTO->getUrl()==null) ? "Bookmark removed" : "Bookmark updated";
				$resourceDTO = ($bookmarkDTO->getUrl()==null) ? (new BaseTransferObject($this->app)) : $bookmarkDTO;

				$responseDTO->setStatuscode(Response::HTTP_OK);
				$responseDTO->setMessage($message);
				$responseDTO->setResource($resourceDTO);

			} else {
				$responseDTO->setStatuscode(Response::HTTP_INTERNAL_SERVER_ERROR);
				$responseDTO->setMessage('Url not updated, internal error has ocourred');
				$responseDTO->setResource(new BaseTransferObject($this->app));

			}
		} else {
			$responseDTO = $tokenAuth;
		}

		return $responseDTO;
	}	

	public function bookmarkList($idUser, $token) {
		$bookmarkModel = new BookmarkModel($this->app);
		$responseDTO = new ResponseTransferObject($this->app);

		//TODO: ALTERAR VERIFICAÇAO TOKEN/REDIS PARA UM MIDDLEWARE BEFORE NO FUTURO
		$tokenLogic = new TokenLogic($this->app);
		$tokenAuth = $tokenLogic->authenticate($token, $idUser, TokenLogic::TOKEN_TYPE_ADMIN);

		if($tokenAuth->getStatuscode()==Response::HTTP_OK) {
			$rs = $bookmarkModel->find(array(
						'id_user'=>$idUser,
						'active'=>1
					),
					array('id', 'url'));

			$bookmarks = array_map(function($t){
				return array('id'=>$t['id'], 'url'=>$t['url']);
			}, $rs);

			$bookmarkListDTO = new BookmarkTransferObject($this->app);
			$bookmarkListDTO->setBookmarkList($bookmarks);

			$responseDTO->setStatuscode(Response::HTTP_OK);
			$responseDTO->setMessage(count($bookmarks));
			$responseDTO->setResource($bookmarkListDTO);
		} else {
			$responseDTO = $tokenAuth;
		}

		return $responseDTO;
	}

}