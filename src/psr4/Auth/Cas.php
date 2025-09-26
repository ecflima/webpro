<?php

namespace Ecfl\Webpro\Auth;

class Cas {

	private $casServerUrl;
	private $callbackUrl;

	public function __construct($casServerUrl = null, $callbackUrl = null) {
		$this->casServerUrl = $casServerUrl !== null ? $casServerUrl : $_ENV['CAS_URL'];
		$this->callbackUrl = $callbackUrl !== null ? $callbackUrl : $_ENV['APP_URL'].'/auth/cas';
	}

	public function login($renew = false) {	
		$tu = $this->casServerUrl.'/login?'.http_build_query([
			"service" => $this->callbackUrl,
			"renew" => $renew
		]);
		http_redirect($tu);
	}

	public function validateTicket($ticket) {
		//var_dump($_GET); die;
		$validation_url = $this->casServerUrl.'/validate?'.http_build_query([
			"ticket" => $ticket,
			"service" => $this->callbackUrl
		]);
		return http_request($validation_url);
	}

	public function validateServiceTicket($ticket) {
		$validation_url = $this->casServerUrl.'/serviceValidate?'.http_build_query([
			"ticket" => $ticket,
			"service" => $service_url		
		]);
		return $this->parseUserFromXMLResponse(http_request($validation_url));
	}

	public function parseUserFromXMLResponse($response) {	
		$xml = new \DOMDocument();
		$xml->loadXML($response);
		$service_response = $xml->firstElementChild;
		$a = $service_response->firstElementChild;
		switch ($a->tagName) {
		case "cas:authenticationFailure":
			return new \Exception("Authentication Failure:".$a->getAttribute('code').':'.$a->textContent);
		case "cas:authenticationSuccess":
			$u = [];			
			foreach ($a->childNodes as $n) {
				switch ($n->tagName) {
					case "cas:user": 
						$u['user'] = $n->textContent; 
						break;
					case 'cas:attributes': {
						$u['attributes'] = [];
						foreach ($n->childNodes as $an) {						
							if ($an->nodeType === XML_CDATA_SECTION_NODE) {
								$v = $an->firstChild->textContent;
							} else {
								$v = $an->textContent;
							}
							$u['attributes'][$an->localName] = trim($v);
						}
						break;
					}
				}
			}		
			return $u;
		}
	}
}