<?php

namespace Ecfl\Webpro;

class App {

	private $dispatcher;

	public function __construct() {
	}

	public function handleRequest($context = null) {
		// setup router
		$this->dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {	
			foreach (Path::getFunctions() as $h => $ra) {
				$attr = $ra->newInstance();	
				$p = $attr->getPath();
				$m = $attr->getMethods();		
		    	$r->addRoute($m, $p, $h);		
			}
		});

		//despacho

		// Fetch method and URI from somewhere
		$httpMethod = $_SERVER['REQUEST_METHOD'];
		$uri = $_SERVER['REQUEST_URI'];

		// Strip query string (?foo=bar) and decode URI
		if (false !== $pos = strpos($uri, '?')) {
		    $uri = substr($uri, 0, $pos);
		}
		$uri = rawurldecode($uri);

		$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
		switch ($routeInfo[0]) {
		    case FastRoute\Dispatcher::NOT_FOUND:
		        // ... 404 Not Found
		    	http_response_code(404);
		    	echo "Not Found";
		        break;
		    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		        $allowedMethods = $routeInfo[1];
		        // ... 405 Method Not Allowed
		        http_response_code(405);		        
		        echo "Method Not Allowed";
		        break;
		    case FastRoute\Dispatcher::FOUND:
		        $handler = $routeInfo[1];
		        $vars = $routeInfo[2];
		        // ... call $handler with $vars
		        try {
		        	call_user_func_array($handler, $vars);
		        } catch (\Throwable $t) {
		        	http_response_code(500);
		        	echo "Internal Server Error";
		        }
		        break;
		}

		//TODO: response
	}	

	public function dump() {
		echo '<h2>Routes</h2>';
		foreach (Path::getFunctions() as $h => $ra) {
			$attr = $ra->newInstance();	
			$p = $attr->getPath();
			echo "<table>";
			foreach($attr->getMethods() as $m) {
				echo "<tr><td>$m</td><td>$p</td><td>$h</td></tr>\n";
			}
			echo "</table>";
		}

		//TODO: Dump middlewares
	}

}