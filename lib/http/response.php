<?php
	namespace HTTP {
		require_once 'signal.php';

		#
		# Properties:
		#  - status
		#  - protocol (=> HTTP\Signal)
		#  - headers (=> HTTP\Signal)
		#  - body (=> HTTP\Signal)
		#
		class Response extends Signal {
			private $status;

			#
			# @param  (string) $name :
			# @return (mixed)        :
			#
			public function __get( $name ) {
				switch ( $name ) {
					case 'status' : return $this->get_status();
					default       : return parent::__get( $name );
				}
			}

			#
			# @param  (string) $name  :
			# @param  (mixed)  $value :
			# @return (void)
			#
			public function __set( $name, $value ) {
				switch ( $name ) {
					case 'status':
						$this->set_status( $value );
					break;

					default:
						parent::__set( $name, $value );
					break;
				}
			}

			#
			# @return (string) :
			#
			public function get_status() {
				return $this->status;
			}

			#
			# @param  (string) $status :
			# @return (void)
			#
			public function set_status( $status ) {
				$this->status = $status;
			}
		}
	}
