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
		$_ENV[$aux[0]]=trim($aux[1]);
		putenv($l);
	}
}



