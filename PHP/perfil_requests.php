<?php
	require 'connect.php';
	require_once 'request_processing.php';
	$rol = getOrGoLogin('rol');
	$pdo =connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
	$formData = loadForm(array(
		'request' => 'text'
	));
	$request = $formData['REQUEST'];
	switch( $request ){
		case 'cambio-nombre':
			$fd = loadForm(array(
				'user' => 'text'
			));
			$user = $fd['USER'];
			try{
				$pdo->query("UPDATE usuario SET usuario='$user' WHERE rol='$rol'");	
				$_SESSION['user'] = $user;
				responseAndDie("OK");
			}catch(Exception $e){
				responseAndDie('Error: No se pudo cambiar el nombre.');
			}
			break;
		case 'cambio-correo':
			$fd = loadForm(array(
				'correo' => 'mail'
			));
			$email = $fd['CORREO'];
			try{
				$pdo->query("UPDATE usuario SET correo='$email' WHERE rol='$rol'");
				responseAndDie("OK");
			}catch(Exception $e){
				responseAndDie('Error: No se pudo cambiar el correo');
			}
	}
?>
