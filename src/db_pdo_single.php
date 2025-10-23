<?php

function db_open() {
	static $connection = null;
	if ($connection === null) {
		$dbdriver = require_env('DB_DRIVER');
		$dbhost = require_env('DB_HOST');
		$dbname = require_env('DB_NAME');
		$dbuser = require_env('DB_USER');
		$dbpass = require_env('DB_PASSWORD');
		$pdn = "$dbdriver:host=$dbhost;dbname=$dbname";
		$connection = new \PDO($pdn, $dbuser, $dbpass, [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
		]);
		if ($dbdriver === "pgsql") {
			$connection->prepare("SET intervalstyle = 'iso_8601'")->execute();
		}
	}
	return $connection;
}

function db_prepare($sql) {	
	return db_open()->prepare($sql);
}

function db_query($sql, $params = []) {	
	$stmt = db_prepare($sql);
	foreach ($params as $k => $v) {
		$stmt->bindValue(":$k", $v);
	}
	$stmt->execute();
	return $stmt->fetchAll();
}

function db_exec($sql, $params = []) {
	$stmt = db_prepare($sql);
	foreach ($params as $k => $v) {
		$stmt->bindValue(":$k", $v);
	}
	$stmt->execute();	
}

function db_template_path($value = null) {
	static $svalue = __DIR__."/storage";
	if ($value === null) {
		return explode(":", $svalue);
	} else {
		$svalue = $value;
	}
}

function db_template_get($templateName) {
	$sp = db_template_path();
	foreach ($sp as $p) {
		$ftp = $p.DIRECTORY_SEPARATOR.$templateName.".sql";
		if (file_exists($ftp)) {
			return file_get_contents($ftp);
		}
	}
	throw new \Exception("Database template \"$templateName\" not found! Search path: $sp");
}

function db_queryt($templateName, $params = []) {	
	return db_query(db_template_get($templateName), $params);
}

function db_exect($templateName, $params = []) {		
	return db_exec(db_template_get($templateName), $params);
}
function db_scriptt($templateName) {	
	db_open()->exec(db_template_get($templateName));
}

