<?php
namespace Controllers;

use Silex\Application;

use Symfony\Component\HttpFoundation\Response;

use BusinessLogic\BookmarkLogic;
use BusinessLogic\DataTransferObject\BookmarkTransferObject;
use BusinessLogic\DataTransferObject\ResponseTransferObject;

class BookmarkController extends BaseController{

	public function create($idUser, Application $app) {
		$idUser = (int)$idUser;
		$bookmarkDTO = new BookmarkTransferObject($app);
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();
		$valuesPost['idUser'] = $idUser;

		$errorResponseDTO = $bookmarkDTO->validate('create', $valuesPost);
		if($errorResponseDTO->getStatuscode()==Response::HTTP_BAD_REQUEST) {
			//houve erro na validaçao, retorna HTTP_BAD_REQUEST 
			$responseDTO = $errorResponseDTO;
		} else {
			//validou bookmarkDTO com OK, segue dados validados para autenticaçao
			$bookmarkLogic = new BookmarkLogic($app);
			$responseDTO = $bookmarkLogic->create($bookmarkDTO, $req->headers->get('x-access-token'));
		}

		return $this->serviceResponse($responseDTO);
	}

	public function edit($idBookmark, Application $app, $post=null) {
		$bookmarkDTO = new BookmarkTransferObject($app);
		$req = $app['request'];

		if($post){
			$valuesPost = $post;
			$validateAction = 'delete';
		} else {
			$req->isMethod('POST');
			$valuesPost = $req->request->all();
			$valuesPost['id'] = (int)$idBookmark;;
			$valuesPost['active'] = 1;

			$validateAction = 'edit';
		}

		$errorResponseDTO = $bookmarkDTO->validate($validateAction, $valuesPost);
		if($errorResponseDTO->getStatuscode()==Response::HTTP_BAD_REQUEST) {
			//houve erro na validaçao, retorna HTTP_BAD_REQUEST 
			$responseDTO = $errorResponseDTO;
		} else {
			//validou bookmarkDTO com OK, segue dados validados para autenticaçao
			$bookmarkLogic = new BookmarkLogic($app);
			$responseDTO = $bookmarkLogic->update($bookmarkDTO, $req->headers->get('x-access-token'));
		}

		return $this->serviceResponse($responseDTO);
	}

	public function delete($idBookmark, Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();
		$valuesPost['id'] = (int)$idBookmark;;
		$valuesPost['active'] = 0;

		return $this->edit($idBookmark, $app, $valuesPost);
	}	



	public function bookmarkList($idUser, Application $app) {
		$idUser = (int)$idUser;
		$req = $app['request'];

		$bookmarkLogic = new BookmarkLogic($app);
		$responseDTO = $bookmarkLogic->bookmarkList($idUser, $req->headers->get('x-access-token'));

		return $this->serviceResponse($responseDTO);
	}	

}