<?php

require_once 'config.php';

function connect(string $host, string $db, string $dbPort, string $user, string $pass) {
	try{
		$dsn = "pgsql:host=$host;port=$dbPort;dbname=$db;";
		return new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	}catch(PDOException $e){
		die("Connection failed: " . $e->getMessage());
	}
}

