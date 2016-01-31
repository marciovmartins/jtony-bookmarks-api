<?php
namespace Controllers;

use BusinessLogic\BookmarkLogic;

use Silex\Application;

use Symfony\Component\HttpFoundation\Response;

class BookmarkController {

	public function create($idUser, Application $app) {
		$idUser = (int)$idUser;
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();
		$valuesPost['id_user'] = $idUser;

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->create($valuesPost, $req->headers->get('x-access-token'));

		return new Response(json_encode($data['message']), $data['statuscode']);
	}

	public function edit($idBookmark, Application $app, $post=null) {
		$req = $app['request'];

		if($post){
			$valuesPost = $post;
		} else {
			$idBookmark = (int)$idBookmark;
				
			$req->isMethod('POST');
			$valuesPost = $req->request->all();
			$valuesPost['id'] = $idBookmark;
		}

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->update($valuesPost, $req->headers->get('x-access-token'));

		return new Response(json_encode($data['message']), $data['statuscode']);
	}

	public function delete($idBookmark, Application $app) {
		$idBookmark = (int)$idBookmark;
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();
		$valuesPost['id'] = $idBookmark;
		$valuesPost['active'] = 0;

		#var_dump($valuesPost);exit;

		return $this->edit($idBookmark, $app, $valuesPost);
	}	



	public function bookmarkList($idUser, Application $app) {
		$idUser = (int)$idUser;
		$req = $app['request'];

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->bookmarkList($idUser, $req->headers->get('x-access-token'));

		return new Response(json_encode($data["message"]), $data['statuscode']);
	}	

}