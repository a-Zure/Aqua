<?php
    /**
     * Aqua
     * Lightweight API router
     * 
     * 
     * Author: aZure
     */

require_once("database.php");
		 
	class Aqua extends Database{
		protected $database = 'Database';

		protected $base_path;
		protected $request_uri;
		protected $request_method;
		protected $auth_token;
		protected $http_methods = array('GET', 'POST', 'PUT', 'PATCH', 'DELETE');

		//Constructor (set base path if needed)
		function __construct($base_path = ''){
			$this->base_path = $base_path;

			//Fix query string
			$this->auth_token = $_SERVER['QUERY_STRING'];
			$this->request_method = $_SERVER['REQUEST_METHOD'];
			$this->request_uri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
		}

		//Map Routes
		public function respond($method, $path, $requires_auth, $cb){
			if($requires_auth == true){
				$a = $this->authenticate($this->auth_token);
			}
			else{ $a = true; }

			if($path == '/'){ $route = $this->base_path; }
			else{ $path = $this->base_path.$path; }

			$params = $this->match_route_vars($path);
			$method = $this->resolve_http_method($method);



			if(is_array($params) && $method == $this->request_method && $a == true){
				call_user_func_array($cb, $params);
				$this->postHandler($this->auth_token);
			}

			if(is_array($params) && $method == $this->request_method && $a == false){
				echo "User not authentified";
			}
		}

		//Resolve http method
		private function resolve_http_method($method) {
			if(in_array($method, $this->http_methods)){
				return $method;
			}else{
				return false;
			}
		}

		//Check Route for possible parameters
		private function match_route_vars($path){
			$vars = array();

			$xreq = explode('/', $this->request_uri);
			$xpath = explode('/', $path);

			if(count($xreq) == count($xpath)){
				foreach($xpath as $i => $value){
					if($xreq[$i] == $value){ continue; }
					elseif(substr($value, 0, 1) == ':' && substr($value, -1, 1) == ':'){
						$x = str_replace(array(':', ':'), '', $value);
						$vars[$x] = $xreq[$i];

						continue;
					}

					return false;
				}

				return $vars;
			}

			return false;
		}

		//Authenticate the user
		private function authenticate($token){
			//insert your authentification method

			if(/*statement*/){
				return false;
			}
			else{ return true; }
		}

		private function postHandler($token){
			// Update token uses or retrieve general information after callback
		}
	}

?>
