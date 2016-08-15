<?php

	namespace App\Exceptions;

	class ViewNotFoundException extends BaseException {

		public function __construct() {
			parent::__construct();
		}

		public function response() {
            $response = new \Symfony\Component\HttpFoundation\Response();
			$response->setStatusCode(404);
			$response->setContent('<h1>View File Not Found</h1>');
			return $response;
		}

	}