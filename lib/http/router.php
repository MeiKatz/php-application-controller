<?php
	namespace HTTP {
		require_once 'request.php';
		require_once 'response.php';
		require_once 'route.php';

		use \Countable;
		use \ArrayAccess;
		use \Iterator;
		use \Exception;

		class Router implements Countable, ArrayAccess, Iterator {
			private $routes = array();

			#
			# @param  (array) $routes : list of instances of HTTP\Route
			# @return (object)        : instance of HTTP\Router
			# @throws Exception (@see #add( Route $route ))
			#
			public function __construct( array $routes = array() ) {
				if ( !empty( $routes ) ) {
					foreach ( $routes as $route ) {
						$this->add( $route );
					}
				}
			}

			#
			# return weather a route is registered or not
			#
			# @param  (object) $route : instance of HTTP\Route
			# @return (bool)          : true if route is registered at the router, else false
			#
			public function contains( Route $route ) {
				return in_array( $this->routes, $route );
			}

			#
			# register a route at the router. you cannot register an instance twice
			#
			# @param  (object) $route : instance of HTTP\Route
			# @return (void)
			# @throws Exception
			#
			public function add( Route $route ) {
				if ( $this->contains( $route ) ) {
					throw new Exception( 'router already knows this route: ' . $route->path );
				}

				$this->routes[] = $route;
			}

			#
			# find a route that matches the passed request
			#
			# @param  (object) $request  : instance of HTTP\Request
			# @param  (object) $response : instance of HTTP\Response
			# @return (object)           : instance of HTTP\Route
			#
			public function route( Request $request, Response $response ) {
				if ( $this->count() === 0 ) {
					return;
				}

				# iterate through list of routes registered at the router
				foreach ( $this->routes as $route ) {
					# return current route if it matches the request
					if ( $route->match( $request, $response ) ) {
						return $route;
					}
				}
			}

			# 
			# return the number of registered routes
			#
			# @return (int) : number of routes registered at the router
			# @see	Countable#count( void )
			#
			public function count() {
				return count( $this->routes );
			}

			#
			# return weather a route is registered or not
			#
			# @param  (object) $route : instance of HTTP\Route
			# @return (bool)          : true if route is registered at the router, else false
			# @see	#contains( Route $route )
			# @see	ArrayAccess#offsetExists( mixed $offset )
			#
			public function offsetExists( $route ) {
				return $this->contains( $route );
			}

			#
			# (disabled)
			#
			# @throws Exception
			# @see	ArrayAccess#offsetGet( mixed $offset )
			#
			public function offsetGet( $offset ) {
				throw new Exception( 'cannot access routes inside the router' );
			}

			#
			# register a route at the router
			#
			# @param  (mixed)  $offset : should be null, else an exception is thrown
			# @param  (object) $route  : instance of HTTP\Route
			# @return (void)
			# @throws Exception
			# @see	#add_route( Route $route )
			# @see	ArrayAccess#offsetSet( mixed $offset, mixed $value )
			#
			public function offsetSet( $offset, $route ) {
				# add routes like this: $router[] = $new_route;
				if ( $offset !== null ) {
					throw new Exception( 'cannot add route to specific position in router' );
				}

				$this->add( $route );
			}

			#
			# (disabled)
			#
			# @throws Exception
			# @see	ArrayAccess#offsetUnset( mixed $offset )
			#
			public function offsetUnset( $offset ) {
				throw new Exception( 'cannot remove route from router' );
			}

			#
			# return the current route
			#
			# @return (object) : current route (instance of HTTP\Route)
			# @see	Iterator#current( void )
			#
			public function current() {
				return current( $this->routes );
			}

			#
			# return the index of the current route
			#
			# @return (scalar) : index of the current route in the router
			# @see	Iterator#key( void )
			#
			public function key() {
				return key( $this->routes );
			}

			#
			# move forward to the next route
			#
			# @return (void)
			# @see	Iterator#next( void )
			#
			public function next() {
				next( $this->routes );
			}

			#
			# rewind the router to the first route
			#
			# @return (void)
			# @see	Iterator#rewind( void )
			#
			public function rewind() {
				reset( $this->routes );
			}

			#
			# check if current position is valid
			#
			# @return (bool) : true if index is valid, else false
			# @see	Iterator#valid( void )
			#
			public function valid() {
				return $this->key() !== null;
			}
		}
	}
