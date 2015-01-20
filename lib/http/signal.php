<?php
	namespace HTTP {
		#
		# Properties:
		#  - protocol
		#  - headers
		#  - body
		#
		class Signal {
			private $protocol;
			private $headers = array();
			private $body    = '';

			#
			# @param  (array) $headers : (optional)
			# @return (object)         : instance of HTTP\Signal
			#
			public function __construct( array $headers = array() ) {
				$this->set_headers( $headers );
			}

			#
			# @param (string) $name : name of the corresponding get_* method
			# @return (mixed)       : return value of the corresponding get_* method
			#
			public function __get( $name ) {
				switch ( $name ) {
					case 'protocol' : return $this->get_protocol();
					case 'headers'  : return $this->get_headers();
					case 'body'     : return $this->get_body();
				}
			}

			#
			# @param (string) $name  : name of the corresponding set_* method
			# @param (mixed)  $value : first parameter to be passed to the corresponding set_* method
			# @return (void)
			#
			public function __set( $name, $value ) {
				switch ( $name ) {
					case 'protocol':
						$this->set_protocol( $value );
					break;

					case 'headers':
						$this->set_headers( $value );
					break;

					case 'body':
						$this->set_body( $value );
					break;
				}
			}

			#
			# @return (string) :
			#
			public function get_protocol() {
				return $this->protocol;
			}

			#
			# @param  (string) $protocol :
			# @return (void)
			#
			public function set_protocol( $protocol ) {
				$this->protocol = $protocol;
			}

			#
			# @return (array) :
			#
			public function get_headers() {
				return $this->headers;
			}

			#
			# @param  (array) $headers :
			# @return (void)
			#
			public function set_headers( array $headers ) {
				$this->headers = $headers;
			}

			#
			# @param  (string) $name : header name
			# @return (bool)         : true if header exists, else false
			#
			public function has_header( $name ) {
				return isset( $this->headers[ $name ] );
			}

			#
			# @param  (string) $name :
			# @return (string)       :
			#
			public function get_header( $name ) {
				if ( $this->has_header( $name ) ) {
					return $this->headers[ $name ];
				}
			}

			#
			# @param  (string) $name  :
			# @param  (string) $value :
			# @return (void)
			#
			public function set_header( $name, $value ) {
				if ( $value === null ) {
					unset( $this->headers[ $name ] );
				} else {
					$this->headers[ $name ] = $value;
				}
			}

			#
			# @return (string) :
			#
			public function get_body() {
				return $this->body;
			}

			#
			# @param  (string) $value :
			# @return (void)          :
			#
			public function set_body( $value ) {
				$this->body = $value;
			}
		}
	}
