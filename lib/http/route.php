<?php
	namespace HTTP {
		require_once 'request.php';
		require_once 'response.php';

		use \Closure;

		class Route {
			private $method  = 'GET';
			private $pattern = null;
			private $path    = null;

			#
			# @param (string) $path   : 
			# @param (string) $method : http request method
			# @return (object)        : instance of HTTP\Route
			#
			public function __construct( $path, $method = 'GET', $controller ) {
				$this->pattern = $this->generate_pattern( $path );
				$this->path    = $path;
				$this->method  = $method;

				if ( !is_string( $controller ) && !( $controller instanceof \Closure ) ) {
					throw new Exception( 'controller must be a class name or a closure' );
				}

				$this->controller = $controller;
			}

			#
			# @param  (string) $name : property name (properties are read-only)
			# @return (string)       : value of the accessed property
			#
			public function __get( $name ) {
				switch ( $name ) {
					case 'method'  : return $this->method;
					case 'pattern' : return $this->pattern;
					case 'path'    : return $this->path;
				}
			}

			#
			# @param  (object) $request  : instance of HTTP\Request
			# @param  (object) $response : instance of HTTP\Response
			# @return (bool)             : true if route matches the passed request, else false
			#
			public function match( Request $request, Response $response ) {
				return ( $this->method === $request->method && $this->match_pattern( $request->url ) );
			}

			#
			# @param  (object) $request  : instance of HTTP\Request
			# @param  (object) $response : instance of HTTP\Response
			# @return (object)           : instance of AbstractController
			#
			public function create_controller( Request $request, Response $response ) {
				$controller = $this->controller;

				# execute callback if it is one
				if ( $controller instanceof Closure ) {
					$controller = $controller( $request, $response );
				}

				# controller must be a subclass of AbstractController
				if ( !class_exists( $controller ) || !is_subclass_of( $controller, 'AbstractController' ) ) {
					throw new Exception( 'invalid controller' );
				}

				# create instance of the controller
				return new $controller;
			}

			#
			# @param  (string) $path : 
			# @return (string)       : regex pattern for the given path
			#
			private function generate_pattern( $path ) {
				# @todo
			}

			#
			# @param  (string) $url :
			# @return (bool)        : true if url matches the pattern, else false
			#
			private function match_pattern( $url ) {
				# @todo
			}
		}
	}
