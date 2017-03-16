<?php 
class DataService {
	private $API_base_path;
	
	function __construct($API_base_path){
		$this->API_base_path = $API_base_path;
	}
	
	function getWithParams($route, $params){
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);
		$URL = $this->API_base_path . $route;
		if(isset($params)) $URL.="?" . http_build_query($params);
		
		return file_get_contents($URL, false, stream_context_create($arrContextOptions));
	}
	function get($route){
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
		);
		$URL = $this->API_base_path . $route;
		if(isset($params)) $URL.="?" . http_build_query($params);
	
		return file_get_contents($URL, false, stream_context_create($arrContextOptions));
	}
	function post($route, $params){
		$options = array(
				'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($params)
				),
				"ssl" => array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				)
		);
		$context  = stream_context_create($options);
		return file_get_contents($this->API_base_path . $route, false, $context);
	}
	function deleteRequest($route, $params){
		$arrContextOptions=array(
				"ssl"=>array(
						"verify_peer"=>false,
						"verify_peer_name"=>false,
				),
				'http' => array(
		            'method' => 'DELETE'
		        )
		);
	
		$URL = $this->API_base_path . $route;
		if(isset($params)) $URL.="?" . http_build_query($params);
	
		return file_get_contents($URL, false, stream_context_create($arrContextOptions));
	}
}

$DataService = new DataService ( SITE_URL . "/API/public/index.php/" );
?>