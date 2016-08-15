<?php

	namespace App\Exceptions;

	class MethodException extends BaseException {

		public function __construct() {
			parent::__construct();
		}

		public function response() {

            $response = new \Symfony\Component\HttpFoundation\Response();
			$response->setStatusCode(405);
			$response->setContent('<h1>Method not allowed</h1>');
			return $response;
		}

	}