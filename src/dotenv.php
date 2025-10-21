<?php

function load_dotenv($filename=".env") {
	//TODO: usar contexto
	if (!file_exists($filename)) {
		//echo "file not exists";
	}
	if (($linhas = file($filename)) === false) {
		//echo "no content";
		return;
	}
	foreach ($linhas as $l) {
		$aux = explode("=", $l);
		putenv($l);
	}
}

function require_env($name) {
	$v = getenv($name);
	if ($v === false) {
		throw new \Exception("Missing required environment variable \"$name\"!");
	}
	return $v;
}