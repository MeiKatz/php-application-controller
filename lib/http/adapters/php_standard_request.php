<?php
	namespace HTTP\Adapters {
		require_once '../request.php';

		use \HTTP\Request;

		class PhpStandardRequest {
			private static $instance = null;

			#
			# @return (object) : instance of HTTP\Request (@see #initialize_request( void ))
			#
			public static function instance() {
				return (
					self::$instance === null
						? self::$instance = self::initialize_request()
						: self::$instance
				);
			}

			#
			# (disabled)
			#
			private function __construct() {}

			#
			# @return (object) : initialized instance of HTTP\Request
			#
			private static function initialize_request() {
					# determine uri, method and headers of the http request
					$uri     = self::fetch_uri(     $_SERVER );
					$method  = self::fetch_method(  $_SERVER );
					$headers = self::fetch_headers( $_SERVER );

					# create instance of HTTP\Request
					$request = new Request( $uri, $method, $headers );
					
					# determine scheme, protocol and body content
					$request->set_scheme(   self::fetch_scheme(   $_SERVER ) );
					$request->set_protocol( self::fetch_protocol( $_SERVER ) );
					$request->set_body(     self::fetch_body()               );

					# pass request to the outside
					return $request;
				}

				#
				# determine request uri
				#
				# @param  (array) $server : $_SERVER-like array
				# @return (string)        : uri of the request
				#
				private static function fetch_uri( array $server ) {
					if ( isset( $server[ 'REQUEST_URI' ] ) ) {
						return $server[ 'REQUEST_URI' ];
					} else {
						return '/';
					}
				}

				#
				# determine request method
				#
				# @param  (array) $server : $_SERVER-like array
				# @return (string)        : method of the request
				#
				private static function fetch_method( array $server ) {
					if ( isset( $server[ 'REQUEST_METHOD' ] ) ) {
						return $server[ 'REQUEST_METHOD' ];
					} else {
						return 'GET';
					}
				}

				#
				# determine headers
				#
				# @param  (array) $server : $_SERVER-like array
				# @return (array)         : hash of headers of the request
				#
				private static function fetch_headers( array $server ) {
					$headers = array();

					foreach ( $server as $name => $value ) {
						if ( substr( $name, 0, 5 ) === 'HTTP_' ) {
							$name = self::parse_header_name( $name );
							$headers[ $name ] = $value;
						}
					}

					# authentication
					# -- basic authentication method
					if ( isset( $server[ 'PHP_AUTH_USER' ] ) && isset( $server[ 'PHP_AUTH_PW' ] ) ) {
						$value = self::generate_basic_auth_header( $server[ 'PHP_AUTH_PW' ], $server[ 'PHP_AUTH_USER' ] );
						$headers[ 'authorization' ] = $value;
					# -- digest authentication method
					} elseif ( isset( $server[ 'PHP_AUTH_DIGEST' ] ) ) {
						$value = self::generate_digest_auth_header( $server[ 'PHP_AUTH_DIGEST' ] );
						$headers[ 'authorization' ] = $value;
					}

					return $headers;
				}

				#
				# determine scheme
				#
				# @param  (array) $server : $_SERVER-like array
				# @return (string)        : scheme of the request
				#
				private static function fetch_scheme( array $server ) {
					if ( isset( $server[ 'REQUEST_SCHEME' ] ) ) {
						return strtolower( $server[ 'REQUEST_SCHEME' ] );
					} elseif ( isset( $server[ 'HTTPS' ] ) && !empty( $server[ 'HTTPS' ] ) ) {
						return 'https';
					} else {
						return 'http';
					}
				}

				#
				# determine http protocol version
				#
				# @param  (array) $server : $_SERVER-like array
				# @retunr (string)        : http protocol version of the request
				#
				private static function fetch_protocol( array $server ) {
					if ( isset( $server[ 'SERVER_PROTOCOL' ] ) ) {
						return $server[ 'SERVER_PROTOCOL' ];
					} else {
						return 'HTTP/1.0';
					}
				}

				#
				# determine body content
				#
				# @return (string) : body of the request
				#
				private static function fetch_body() {
				  # @todo
					$input = file_get_contents( 'php://input' );
				}

				#
				# @param  (string) $name :
				# @return (string)       :
				#
				private static function parse_header_name( $name ) {
					return str_replace( '_', '-', strtolower( substr( $name, 5 ) ) );
				}

				#
				# @param  (string) $value :
				# @return (string)        :
				#
				private static function generate_digest_auth_header( $value ) {
					return 'Digest ' . $value;
				}

				#
				# @param  (string) $user     :
				# @param  (string) $password :
				# @return (string)           :
				#
				private static function generate_basic_auth_header( $user, $password ) {
					return 'Basic ' . base64_encode( $user . ':' . $password );
				}
			}
		}
	}
