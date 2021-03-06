<?php

require_once 'config.php';

function connect(string $host, string $db, string $dbPort, string $user, string $pass) {
	try{
		$dsn = "pgsql:host=$host;port=$dbPort;dbname=$db;";
		return new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_PERSISTENT => true]);

	}catch(PDOException $e){
		die("Connection failed: " . $e->getMessage());
	}
}


function getOrGoLogin($parameter){
	if(session_status() == PHP_SESSION_NONE) session_start();
	$p = $_SESSION[$parameter];
	if(empty($p)){
		header('location: auth.php',true);
		die();
	}
	return $p;
}
