<?hh //strict
class PageIgnoreException extends ErrorException { }

class APIException extends ErrorException {
	public function __construct(string $message = 'Something went wrong') {
		parent::__construct($message);
	}
}

class APIFieldException extends ErrorException {
	public function __construct(public string $name = '', ?string $message = null) {
		parent::__construct();
		$this->message = $message === null ? 'Please enter a valid '. $name : $message;
	}
}

class APIFieldMultiException extends ErrorException {
	public function __construct(public array<APIFieldException> $Errors) {
		parent::__construct();
	}
}

class HTTPException extends ErrorException {
	public int $httpCode;
	public function __construct(int $code) {
		$this->httpCode = (int) $code;
		parent::__construct("HTTP Error $code");
	}
}
class HTTPUnavailableException extends HTTPException {
	public function __construct(string $message) {
		parent::__construct(500);
		$this->message = $message;
	}
}

class HTTPRedirectException extends HTTPException {
	public string $redirectUri;
	public function __construct(string $uri) {
		parent::__construct(302);
		$this->redirectUri = $uri;
	}
}
