<?php

class Response{

	private $code_num = 200;
	private $code_message = 'OK';
	private $data = null;
	private $message = 'OK';
	private $has_error = false;

	public function __construct() {}

	public function setData($data){
		$this->data = $data;
		return $this;
	}

	public function setError($message, $code=500){
		$this->has_error = true;
		$this->message = $message;
		$this->setResponseCode($code);
		return $this;
	}

	public function setResponseCode($num){
		$num = intval($num);
		$http_status_codes = [
			100 => "Continue",
			101 => "Switching Protocols",
			102 => "Processing",
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			203 => "Non-Authoritative Information",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",
			207 => "Multi-Status",
			300 => "Multiple Choices",
			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			305 => "Use Proxy",
			306 => "(Unused)",
			307 => "Temporary Redirect",
			308 => "Permanent Redirect",
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Timeout",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Request Entity Too Large",
			414 => "Request-URI Too Long",
			415 => "Unsupported Media Type",
			416 => "Requested Range Not Satisfiable",
			417 => "Expectation Failed",
			418 => "I'm a teapot",
			419 => "Authentication Timeout",
			420 => "Enhance Your Calm",
			422 => "Unprocessable Entity",
			423 => "Locked",
			424 => "Failed Dependency",
			425 => "Unordered Collection",
			426 => "Upgrade Required",
			428 => "Precondition Required",
			429 => "Too Many Requests",
			431 => "Request Header Fields Too Large",
			444 => "No Response",
			449 => "Retry With",
			450 => "Blocked by Windows Parental Controls",
			451 => "Unavailable For Legal Reasons",
			494 => "Request Header Too Large",
			495 => "Cert Error",
			496 => "No Cert",
			497 => "HTTP to HTTPS",
			499 => "Client Closed Request",
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Timeout",
			505 => "HTTP Version Not Supported",
			506 => "Variant Also Negotiates",
			507 => "Insufficient Storage",
			508 => "Loop Detected",
			509 => "Bandwidth Limit Exceeded",
			510 => "Not Extended",
			511 => "Network Authentication Required",
			598 => "Network read timeout error",
			599 => "Network connect timeout error"
		];
		if(!array_key_exists($num, $http_status_codes)) $num = 500;
		$this->code_num = $num;
		$this->code_message = $http_status_codes[$num];
		return $this;
	}

	public function send(){
		header("HTTP/1.0 ".$this->code_num." ".$this->code_message);
		$response_object = [
			'has_error' => $this->has_error,
			'data' => $this->data,
			'message' => $this->message
		];
		header("Content-type: application/json");
		echo json_encode($response_object, JSON_PRETTY_PRINT);
		exit;
	}

}