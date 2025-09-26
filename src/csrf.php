<?php

function csrf_token() {
	#TODO: usar contexto
	static $token = null;
	if ($token === null) {
		$_SESSION['csrf'] = $token = bin2hex(random_bytes(16));
		setcookie("X-csrf", $token, time()+3600);
	}
	return $token;
}

function csrf_check() {
	#TODO: usar contexto
	if ($_SESSION['csrf'] !== $_COOKIE['X-csrf']) {
		http_response(419, "Page Expired");
		die;
	}
}

