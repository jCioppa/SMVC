<?php

	namespace App\Exceptions\Handler;

	class Handler {

		public function __construct() {

		}

		public function toResponse($e) {
			
			if ($e instanceof \App\Exceptions\BaseException)
				return $e->response();	
			else {
                $response = new \Symfony\Component\HttpFoundation\Response(); 
                $response->setStatusCode(500);
                $response->setStatusCode($e->getMessage());
                return $response;
			}
		}

	}