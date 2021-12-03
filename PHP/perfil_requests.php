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
			break;
		case 'cambio-password':
			$fd = loadForm(array(
				'password_actual' => 'password',
				'password_nueva' => 'password'
			));
			$password_nueva = $fd['PASSWORD_NUEVA'];
			$password_actual = $fd['PASSWORD_ACTUAL'];
			try{
				$results = $pdo->query("SELECT contrasena FROM usuario WHERE rol='$rol'");
				$password_bbdd = "";
				foreach($results as $fila){
					$password_bbdd = $fila['contrasena'];
				}
				if( password_verify($password_actual,$password_bbdd) ){
					$password_nueva = password_hash($password_nueva,PASSWORD_BCRYPT);
					$pdo->query("UPDATE usuario SET contrasena='$password_nueva' WHERE rol='$rol'");
					responseAndDie("OK");
				}else{
					responseAndDie("Error: Contraseña actual incorrecta");
				}
			}catch(Exception $e){
				responseAndDie("Error: No se pudo cambiar contraseña");
			}
			break;
		case 'eliminar-perfil':
			try{
				$pdo->query("DELETE from usuario WHERE rol='$rol'");
				session_destroy();
				responseAndDie('OK');
			}catch(Exception $e){
				responseAndDie("Error: No se pudo borrar la cuenta");
			}
			break;
	}
?>
