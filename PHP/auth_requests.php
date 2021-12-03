<?php	
	require 'connect.php';
	require_once('request_processing.php');

	function login($user,$rol){
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['rol'] = $rol;
	}

	$formData = loadForm(array(
		'request' => 'text',
		'rol' => 'text',
		'password' => 'password'
	));
	$request = $formData['REQUEST'];
	$rol = $formData['ROL'];
	$password = $formData['PASSWORD'];

	switch($request){
		case "LOGIN":
			$pdo = connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
			$query = "SELECT contrasena, usuario FROM usuario WHERE rol='$rol'";
			try{
				$results = $pdo->query($query);
				foreach( $results as $fila ){
					$pass = $fila['contrasena'];
					$user = $fila['usuario'];
				}
				$pdo = null;
				if( password_verify($password,$pass) ) {
					login($user,$rol);
					responseAndDie('OK');
				}
				else responseAndDie('Usuario o contraseña no existente.');
			}catch(Exception $e){
				responseAndDie('ERROR');
			}
			break;
		case "REGISTER":
			$formData = loadForm(array(
				'user' => 'text',
				'email' => 'email',
				'birthday' => 'date',
				'password-confirm' => 'password'
			));	
			$user = $formData['USER'];
			$email = $formData['EMAIL'];
			$birthday = $formData['BIRTHDAY'];
			if( $password !== $formData['PASSWORD-CONFIRM'] ) responseAndDie('ERROR Contraseñas no coinciden');
			$password = password_hash($password,PASSWORD_BCRYPT);
			$pdo = connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
			$query= "INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('$rol', '$user', '$password', '$email', TO_DATE('$birthday', 'YYYY-MM-DD'))";
			try{
				$results = $pdo->query($query);
				login($user,$rol);
				$pdo = null;		
				responseAndDie('OK');
			}catch(Exception $e){
				if( $e->getCode() === '23505' ) responseAndDie("Error: rol_ocupado");
				else responseAndDie( $e->getMessage() );
			}
			break;
	}
?>
