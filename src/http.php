<?php

function http_redirect($to) {
	//TODO: opção die
	header('Location: '.$to);
}

function http_response($code, $message) {
	//TODO: opção die
	http_response_code($code);
	echo $message;	
}

function http_request($url) {
	//TODO: viabilizar outros drivers além do curl
	$r = curl_init();
	curl_setopt($r, CURLOPT_URL, $url);
	curl_setopt($r, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($r);
	if(!curl_errno($r)){
		return $response;
	} else {
		throw new \Exception(curl_error($r));
	}
}