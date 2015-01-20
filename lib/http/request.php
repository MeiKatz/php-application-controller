<?php
	namespace HTTP {
		require_once 'signal.php';
		
		#
		# Properties:
		#  - method
		#  - url
		#  - scheme
		#  - secure
		#  - protocol (=> HTTP\Signal)
		#  - headers (=> HTTP\Signal)
		#  - body (=> HTTP\Signal)
		#
		class Request extends Signal {
			private $method;
			private $url;
			private $scheme;

			#
			# @param  (string) $url     :
			# @param  (string) $method  : (optional)
			# @param  (array)  $headers : (optional)
			# @return (object)          : instance of HTTP\Request
			#
			public function __construct( $url, $method = 'GET', array $headers = array() ) {
				$this->set_url( $url );
				$this->set_method( $method );
				parent::__construct( $headers );
			}

			#
			# @param  (string) $name :
			# @return (mixed)
			#
			public function __get( $name ) {
				switch ( $name ) {
					case 'method'  : return $this->get_method();
					case 'url'     : return $this->get_url();
					case 'scheme'  : return $this->get_scheme();
					case 'secure'  : return $this->is_secure();
					default        : return parent::__get( $name );
				}
			}

			#
			# @param  (string) $name  :
			# @param  (mixed)  $value :
			# @return (void)
			#
			public function __set( $name, $value ) {
				switch ( $name ) {
					case 'method':
						$this->set_method( $value );
					break;

					case 'url':
						$this->set_url( $value );
					break;

					case 'scheme':
						$this->set_scheme( $value );
					break;

					default:
						parent::__set( $name, $value );
					break;
				}
			}

			#
			# @return (string) :
			#
			public function get_method() {
				return $this->method;
			}

			#
			# @param  (string) $method :
			# @return (void)
			#
			public function set_method( $method ) {
				$this->method = $method;
			}

			#
			# @return (string) :
			#
			public function get_url() {
				return $this->url;
			}

			#
			# @param  (string) $url :
			# @return (void)
			#
			public function set_url( $url ) {
				$this->url = $url;
			}

			#
			# @return (string) :
			#
			public function get_scheme() {
				return $this->scheme;
			}

			#
			# @param  (string) $scheme :
			# @return (void)
			#
			public function set_scheme( $scheme ) {
				$this->scheme = strtolower( $scheme );
			}

			#
			# @return (bool) :
			#
			public function is_secure() {
				return ( $this->scheme === 'https' );
			}
		}
	}
