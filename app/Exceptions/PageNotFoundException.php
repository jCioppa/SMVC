<?php

	namespace App\Exceptions;

	class PageNotFoundException extends BaseException {

		public function __construct() {
			parent::__construct();
		}

		public function response() {
            $response = new \Symfony\Component\HttpFoundation\Response();
			$response->setStatusCode(404);
			$response->setContent('<h1>Page not found</h1>');
			return $response;
		}

	}