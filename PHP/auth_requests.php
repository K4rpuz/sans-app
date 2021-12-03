<?php	
	require 'connect.php';
	require_once('request_processing.php');
	$formData = loadForm(array(
		'request' => 'text',
		'user' => 'text',
		'password' => 'password'
	));
	$request = $formData['REQUEST'];
	$user = $formData['USER'];
	$password = $formData['PASSWORD'];

	switch($request){
		case "LOGIN":
			break;
		case "REGISTER":
			$formData = loadForm(array(
				'rol' => 'text',
				'email' => 'email',
				'birthday' => 'date',
				'password-confirm' => 'password'
			));	
			$rol = $formData['ROL'];
			$email = $formData['EMAIL'];
			$birthday = $formData['BIRTHDAY'];
			if( $password !== $formData['PASSWORD-CONFIRM'] ) responseAndDie('ERROR Contraseñas no coinciden');
			$pdo = connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
			$birthday = '12-12-2012';
			$query= "INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('$rol', '$user', '$password', '$email', TO_DATE('$birthday', 'DD-MM-YYYY'))";
			$pdo->query("SELECT * FROM usuario");

			responseAndDie("ESTA ES LA QUERY: ".$query);
			if($results->fetch()){
				responseAndDie('OK');
			}else{
				responseAndDie('ERROR: ');
			}
			break;
	}
?>
