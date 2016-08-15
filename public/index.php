<?php

        require_once ('../vendor/autoload.php');        
        require_once ('../config/functions.php');
        
        use Symfony\Component\HttpFoundation\Request;

        $app = require_once ('../bootstrap/app.php');

        $request = Request::createFromGlobals();

        $response = $app->handle($request);

        $response->send();

        $app->terminate($request, $response);


