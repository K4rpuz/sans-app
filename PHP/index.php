
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="../IMG/logo.jpeg" type="image/x-icon">
	<link rel="stylesheet" href="../CSS/normalize.css">	
	<link rel="stylesheet" href="../CSS/styles.css">	
	<title>SansApp</title>
</head>
<body>
	<header>
		
		<div class="header-element">
			<a href="#" class="logo-img">
				<img src="../IMG/logo.jpeg" alt="logo">
			</a>
			<section class="barra-busqueda">	
				<form>
					<input type="text" name="search" id="" placeholder="Buscar...">
				</form>
				<div class="barra-busqueda__resultados"></div>
			</section>
		</div>
		
		<nav>
			<div class="navbar-element">
				<?php
						session_start();
						$user = $_SESSION['user'];
						if( empty($user) ) echo '<a href="auth.php">Iniciar Sesión</a>';
						else echo '<a href="perfil.php">'.$user.'</a>';	
				?>
			</div>
		</nav>
	</header>
	<script type="module" src="../JS/busqueda.js"></script>
</body>
</html>
<?php

	require 'connect.php';
	$pdo =connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
	foreach ($pdo->query('SELECT * FROM usuario') as $row) {
		$rol = $row['rol'];
		$user = $row['usuario'];
		$password = $row['contrasena'];
		$email = $row['correo'];
		$birthday = $row['nacimiento'];
		$password = password_hash($password,PASSWORD_BCRYPT);
		$query= "INSERT INTO usuario(rol, usuario, contrasena, correo, nacimiento) VALUES ('$rol', '$user', '$password', '$email', TO_DATE('$birthday', 'YYYY-MM-DD'))";
		echo $query . '<br>';
	}

?>
