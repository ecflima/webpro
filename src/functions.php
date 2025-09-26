<?php

function load_dotenv($filename=".env") {
	if (!file_exists($filename)) {
		echo "file not exists";
	}
	if (($linhas = file(__DIR__.DIRECTORY_SEPARATOR.$filename)) === false) {
		echo "no content";
		return;
	}
	foreach ($linhas as $l) {
		$aux = explode("=", $l);
		$_ENV[$aux[0]]=trim($aux[1]);
		putenv($l);
	}
}

function redirect($to) {
	header('Location: '.$to);
	die;
}

function request($url) {
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

function csrf_token() {
	static $token = null;
	if ($token === null) {
		$_SESSION['csrf'] = $token = bin2hex(random_bytes(16));
		setcookie("X-csrf", $token, time()+3600);
	}
	return $token;
}

function csrf_check() {
	if ($_SESSION['csrf'] !== $_COOKIE['X-csrf']) {
		http_response_code(419);
		echo "Page Expired";
		die;
	}
}
