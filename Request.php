<?php

namespace Signature\Authentication;

class Request{
	//contain method like POST
	protected $method;

	//link to your API
	protected $path;

	//cotain param that will Authenticate
	protected $params;

	//contain version of API
	protected $version = '1.0';

	//contain all Auth common params
	protected $general_auth = [ 'auth_version' => null,  'auth_key' => null,  'auth_timestamp' => null,  'auth_signature' => null ];
    
    //this will contain/cover your additional/costum Auth params
    protected $user_params = [];

	public function __construct($method,  $path,  $params){
		//make to all method request is uppercase
		$this->method = strtoupper($method);

		$this->path = $path;

		foreach($params as $key => $val){
			//make to all key of array data is lowercase
			$key = strtolower($key);
            
            //check out and ensure the key of data is auth_ prefix
			substr($key,  0,  5) == 'auth_' ? $this->general_auth[$key] = $val : $this->user_params[$key] = $val;
		}
	}
    
    //The method to add auth_signature to array groups
	public function sign(Token $token){
		$this->general_auth = [
		'auth_version' => '1.0',
		'auth_key' => $token->getKey(),
		'auth_timestamp' => time()
		];

		$this->general_auth['auth_signature'] = $this->signature($token);
		return $this->general_auth;
	}

    //the method will signature a every token  request to hash_hmac
	public function signature(Token $token){
		return hash_hmac('sha256', $this->binding_signature(),  $token->getToken());
	}
    //implode all data of array
	public function binding_signature(){
		return implode('\n',  [ $this->method,  $this->path, $this->merger_signature() ]);
	}

	public function merger_signature(){
		//cover all data array
		$param = [];

        //merge general_auth and user_params
		$auth = array_merge($this->general_auth,  $this->user_params);

		foreach($auth as $key => $val){
			//make to all key to lowercase
			$param[strtolower($key)] = $val;
		}
        //clear auth
		unset($param['auth_signature']);
        
		return http_build_query($param);
	}
    
    /*authenticate-validateSignature
     *
     *Contain to validation all of request
     *
     */
    
	public function authenticate(Token $token, $timeLimiter = 1000){
		if($this->general_auth['auth_key'] == $token->getKey()){
			return $this->authenticator($token, $timeLimiter);
		}
		throw new \Exception("The auth_key is incorrect!");
		
	}

	public function authenticator(Token $token,  $timeLimiter){
		if(!$token->getToken()){
		throw new \Exception('The secret is incorrect!');	
		}

        return true;

		return $this->validateVersion();
		return $this->validateTimestamp();
		return $this->validateSignature($token);

		
		}
	

	public function validateVersion(){
		if($this->general_auth['auth_version'] !== $this->version){
			throw new \Exception('The number version is missing!');
		}
		return true;
	}

	public function validateTimestamp($timeLimiter){
		$timeTester = $this->general_auth['auth_timestamp'] - time();
		if($timeTester >= $timeLimiter){
			throw new \Exception("The time was expired!");
		}
		return true;
	}

	public function validateSignature(Token $token){
		if($this->general_auth['auth_signature'] !== $this->signature($token)){
			throw new \Exception('The auth_signature is incorrect!');
		}
		return true;
	}


}