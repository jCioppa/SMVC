<?php

        namespace App;

        use \Illuminate\Container\Container;
        use \Symfony\Component\Routing\Route;
        use \Symfony\Component\Routing\RequestContext;
        use \Symfony\Component\Routing\Matcher\UrlMatcher;


        trait RouteMatcher { 

                protected $matcher;
                protected $routes;
                protected $route_map = [];

                private function request($url, $options, $method) {

                        $defaults = isset($options['defaults']) ? $options['defaults'] : [];

                        $this->routes->add($url, new Route($url, $defaults));

                        $this->route_map[$url] = [
                                'method' =>  $method
                        ];

                        if (isset($options['view']))
                                $this->route_map[$url]['view'] = $options['view'];

                        if (isset($options['_controller']))
                                $this->route_map[$url]['_controller'] = $options['_controller'];

                }

                public function get($url, $options) {
                        $this->request($url, $options, 'GET');
                }

                public function post($url, $options) {
                        $this->request($url, $options, 'POST');
                }

                public function put($url, $options) {
                        $this->request($url, $options, 'PUT');
                }

                public function hasRoute($url) {
                        try {
                                $this->matcher->match($url);
                                return true;
                        } catch(\Exception $e) {
                                return false;
                        }
                }
        }

        class Application extends Container {

                use RouteMatcher;

                protected $handler;
                protected $context;

                public function __construct() {
                        $this->handler = new \App\Exceptions\Handler\Handler;
                        $this->routes = new \Symfony\Component\Routing\RouteCollection;
                        $this->context = new RequestContext();
                }

                public function fetchUrl() {
                        $url = isset($_GET['url']) ? '/' . $_GET['url'] : '/';
                        $matches = $this->matcher->match($url);
                        return $matches['_route'];
                }

                public function handle(\Symfony\Component\HttpFoundation\Request $request) {

                        $this->context->fromRequest($request);
                        $this->matcher = new UrlMatcher($this->routes, $this->context);

                        try {

                                $url = $this->fetchUrl();

                                if ($this->hasRoute($url)) {
                                        return $this->handleFoundRoute($request, $url) ;
                                } 

                                throw new \App\Exceptions\PageNotFoundException;

                        } catch (\Exception $e) {

                                return $this->handler->toResponse($e);
                                
                        }
                }

                public function terminate($request, $response) {

                }

                public function handleFoundRoute($request, $url) {

                        if ($request->getMethod() == $this->route_map[$url]['method'])  {
                                return $this->handleMatchedRoute($request, $url);
                        } 

                        throw new \App\Exceptions\MethodException;
                }

                public function handleMatchedRoute(\Symfony\Component\HttpFoundation\Request $request, $url) {

                        $response = new \Symfony\Component\HttpFoundation\Response();

                        // provided a view file
                        if (isset($this->route_map[$url]['view'])) {

                                $view_file = __DIR__ . '/../resources/views/' . $this->route_map[$url]['view'] . '.php';

                                if (!file_exists($view_file))
                                        throw new \App\Exceptions\ViewNotFoundException;

                                $matches = $this->matcher->match('/' . $_GET['url']);

                                return $this->render_view($request, $response, $view_file, $matches);

                        } else if (isset($this->route_map[$url]['_controller'])) {

                                $controller_resolver = 
                                        new \Symfony\Component\HttpKernel\Controller\ControllerResolver;
                                 
                                $argument_resolver = 
                                        new \Symfony\Component\HttpKernel\Controller\ArgumentResolver;

                                 

                                $controller = $controller_resolver->getController($request) ;

                                $arguments = $argument_resolver->getArguments($request, $con);

                        }

                }

                public function render_view($request, $response, $file, $matches) {

                        $response->setStatusCode(200);
                        ob_start();
                        

                        foreach ($matches as $key => $val)  {
                                if ($key == '_route')
                                        continue;
                                $$key = $val;
                        }

                        include ($file);
                        $response->setContent(ob_get_clean());
                        return $response;
                }



        }
        
?>
        
