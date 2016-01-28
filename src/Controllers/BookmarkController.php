<?php
namespace Controllers;

use BusinessLogic\BookmarkLogic;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class BookmarkController {

	public function create(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->create($valuesPost);

		return new Response(json_encode($data), $data['statuscode']);
	}

	public function edit(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->update($valuesPost);

		return new Response(json_encode($data), $data['statuscode']);
	}	

	public function bookmarkList(Application $app) {
		$req = $app['request'];
			
		$req->isMethod('POST');
		$valuesPost = $req->request->all();

		//TODO: VALIDAR CAMPOS COM DTO PASSAR O DTO PARA LOGIC

		$bookmarkLogic = new BookmarkLogic($app);
		$data = $bookmarkLogic->bookmarkList($valuesPost);

		return new Response(json_encode($data), $data['statuscode']);
	}

}