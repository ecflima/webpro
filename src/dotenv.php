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
	$value = getenv($name);
	if ($value === false) {
		throw new \Exception("Environment variable \"$name\" not defined!");
	}
	return trim($value);
}