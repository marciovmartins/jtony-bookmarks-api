<?php

namespace BusinessLogic\DataTransferObject;

use Silex\Application;

class ResponseTransferObject extends BaseTransferObject {

	private $statuscode;
	private $message;
	private $resource;
	private $token;

	private $tokenIdUser;
	private $tokenTypeUser;	

	public function __construct(Application $app) {
		parent::__construct($app);
	}

	public function getStatuscode() {
		return $this->statuscode;
	}

	public function setStatuscode($statuscode) {
		$this->statuscode = $statuscode;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

	public function getResource() {
		return $this->resource;
	}

	public function setResource(BaseTransferObject $resource) {
		$this->resource = $resource;
	}	

	public function getToken() {
		return $this->token;
	}

	public function setToken($token) {
		$this->token = $token;
	}

	public function setTokenIdUser($tokenIdUser) {
		$this->tokenIdUser = $tokenIdUser;
	}

	public function getTokenIdUser() {
		return $this->tokenIdUser;
	}

	public function setTokenTypeUser($tokenTypeUser) {
		$this->tokenTypeUser = $tokenTypeUser;
	}

	public function getTokenTypeUser() {
		return $this->tokenTypeUser;
	}	

}