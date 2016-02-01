<?php

namespace BusinessLogic\DataTransferObject;

use Symfony\Component\HttpFoundation\Response;

use Silex\Application;

class BaseTransferObject {

	protected $app;
	protected $validateActions = [];

	public function __construct(Application $app) {
		$this->app = $app;
	}

	public function validate($validateAction, array $valuesPost=null) {
		$fieldsToValidate = [];
		$message = [];
		$responseDTO = new ResponseTransferObject($this->app);

		foreach ($this->validateActions[$validateAction]['fields'] as $key => $value) {
			if($valuesPost){
				//Coloco na validaçao o que veio no valuesPost e seto na classe
				if(!array_key_exists($value, $valuesPost) || !isset($valuesPost[$value])) {
					$fieldsToValidate[$value] = '';
				} else {
					$fieldsToValidate[$value] = $valuesPost[$value];
				}

				$this->$value = isset($valuesPost[$value]) ? $valuesPost[$value] : '';
			} else {
				//Coloco na validaçao o que esta setado na classe
				$fieldsToValidate[$value] = $this->$value;
			}
		}

		$errors = $this->app['validator']->validateValue($fieldsToValidate, $this->validateActions[$validateAction]['constraint']);
		
		if (count($errors) > 0) {
		    foreach ($errors as $error) {
		        $message[str_replace(array('[',']'), '', $error->getPropertyPath())] = $error->getMessage();
		    }
			$responseDTO->setStatuscode(Response::HTTP_BAD_REQUEST);
		} else {
			$className = explode('\\', get_class($this));
			$message[] = $className[count($className)-1].' have valid content for '.$validateAction;

			$responseDTO->setStatuscode(Response::HTTP_OK);
		}

		$responseDTO->setMessage($message);
		$responseDTO->setResource($this);
		$responseDTO->setToken(null);

		return $responseDTO;
	}	

	public function toArray() {
		return array();
	}	
	
}