
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
				<a href="login.php">Login</a>
			</div>
			<div class="navbar-element">
				<a href="register.php">Crear una cuenta</a>
			</div>
		</nav>
	</header>
	<script type="module" src="../JS/busqueda.js"></script>
</body>
</html>
<?php

	$pdo = require 'connect.php';
	foreach ($pdo->query('SELECT * FROM usuario') as $row) {
		echo $row['rol'] . ' ' . $row['usuario'] . ' ' . $row['correo'] . '<br>';
	}

?>
