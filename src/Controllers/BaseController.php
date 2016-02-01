<?php
namespace Controllers;

use BusinessLogic\DataTransferObject\ResponseTransferObject;

use Symfony\Component\HttpFoundation\Response;

class BaseController {

	protected function serviceResponse(ResponseTransferObject $responseDTO) {
		return new Response(json_encode(array(
					'message'=>$responseDTO->getMessage(), 
					'resource'=>$responseDTO->getResource()->toArray())
				), 
				$responseDTO->getStatuscode(),
				array(
					'x-access-token'=>$responseDTO->getToken()
				)
			);
	}
}
