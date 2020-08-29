<?php
    /**
     * Aqua
     * Lightweight API router based on siesta
     * Siesta: https://github.com/smarulanda/Siesta
     * 
     * 
     * Author: aZure
     */
class Aqua {

	protected $base_path;
	protected $request_uri;
	protected $request_method;
	protected $http_methods = array('get', 'post', 'put', 'patch', 'delete');
	protected $route_vars = array('int' => '/^[0-9]+$/', 'any' => '/^[0-9A-Za-z]+$/');

    //Constructor (set base path if needed)
	function __construct($base_path = ''){
		$this->base_path = $base_path;

		//Fix query string
		$this->request_uri = rtrim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
		$this->request_method = $this->_resolve_http_method();
	}

	//Map Routes
	public function respond($method, $route, $callable){
		$method = strtolower($method);

		if ($route == '/') $route = $this->base_path;
		else $route = $this->base_path . $route;

		$matches = $this->_match_route_vars($route);

		if (is_array($matches) && $method == $this->request_method){
			//Found matching Route
			call_user_func_array($callable, $matches);
		}
	}

	//Resolve http method
	private function _resolve_http_method() {
		$method = strtolower($_SERVER['REQUEST_METHOD']);

		if (in_array($method, $this->http_methods)){
			return $method;
		}
		return 'get';
	}

	//Check Route for possible parameters
	private function _match_route_vars($route){
		$vars = array();

		$xreq = explode('/', $this->request_uri);
		$xroute = explode('/', $route);

		if (count($xreq) == count($xroute)){
			foreach ($xroute as $key => $value){
				if ($value == $xreq[$key]){
					continue;
				}
				elseif ($value[0] == '{' && substr($value, -1) == '}'){
					//Variable detected
					$strip = str_replace(array('{', '}'), '', $value);
					$exp = explode(':', $strip);

                    $var_type = $exp[0];
                    if($var_type == ""){ $var_type = "any"; }

					if (array_key_exists($var_type, $this->route_vars)){
						//Check if variable matches defined pattern
						$pattern = $this->route_vars[$var_type];
						
						if (preg_match($pattern, $xreq[$key])){
							if (isset($exp[1])) {
								$vars[$exp[1]] = $xreq[$key];
							}
							//Match found
							continue;
						}
					}
				}	
				//Mis-match
				return false;
			}
			//Total segments match
			return $vars;
		}
		return false;
	}

	public function checkSubmittedData($required, $submitted){
		$errs = 0;
		foreach($required as $value){
			

			if(array_key_exists($value, $submitted)){
				continue;
			}else{
				echo "Data missing: ".$value;
				$errs++;
			}
		}
		if($errs == 0){
			return true;
		}else{
			return false;
		}
	}
}

?>
