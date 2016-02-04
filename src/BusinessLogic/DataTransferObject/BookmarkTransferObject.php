<?php

namespace BusinessLogic\DataTransferObject;

use Silex\Application;

use Symfony\Component\Validator\Constraints as Assert;

class BookmarkTransferObject extends BaseTransferObject {

	protected $id;
	protected $url;
	protected $idUser;
	protected $active;
	protected $bookmarkList;
	
	public function __construct(Application $app) {
		parent::__construct($app);

		//VALIDATION CONSTRAINTS
		$urlValidation = array(new Assert\NotBlank(), new Assert\Url());

		$idUserValidation = array(new Assert\Range(array(
						'min' => 1,
						'max' => 10000,
						'minMessage' => "minimun user id is {{ limit }}",
						'maxMessage' => "maximun user id is {{ limit }}"
					)
				));

		$idValidation = array(new Assert\Range(array(
						'min' => 1,
						'max' => 100000,
						'minMessage' => "minimun bookmark id is {{ limit }}",
						'maxMessage' => "maximun bookmark id is {{ limit }}"
					))
				);		

		$constraintEdit = new Assert\Collection(array(
			'url' => $urlValidation,
			'id' => $idValidation
		));

		$constraintDelete = new Assert\Collection(array(
			'id' => $idValidation
		));		

		$constraintCreate = new Assert\Collection(array(
			'url' => $urlValidation,
			'idUser' => $idUserValidation
		));

		/*
		$constraintEdit = new Assert\Collection(array(
			'url' => $urlValidation,
			'idUser' => $idUserValidation,
			'idUser' => $idValidation,
		));
		*/		

		$this->validateActions['create'] = array(
													'fields' => array('url', 'idUser'),
													'constraint' => $constraintCreate
												);

		$this->validateActions['edit'] = array(
													'fields' => array('url', 'id'),
													'constraint' => $constraintEdit
												);

		$this->validateActions['delete'] = array(
													'fields' => array('id'),
													'constraint' => $constraintDelete
												);

		$this->validateActions['get'] = $this->validateActions['delete'] ;

	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getIdUser() {
		return $this->idUser;
	}

	public function setIdUser($idUser) {
		$this->idUser = $idUser;
	}

	public function setActive($active) {
		$this->active = $active;
	}	

	public function getActive() {
		return $this->active;
	}

	public function setBookmarkList($bookmarkList) {
		$this->bookmarkList = $bookmarkList;
	}

	public function toArray() {
		return array(
				'id'=>$this->id,
				'url'=>$this->url,
				'idUser'=>$this->idUser,
				'bookmarkList'=>$this->bookmarkList
			);
	}

}
