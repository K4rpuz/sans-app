<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="img/logo.jpeg" type="image/x-icon">
	<link rel="stylesheet" href="styles/normalize.css">	
	<link rel="stylesheet" href="styles/styles.css">	
	<title>SansApp</title>
</head>
<body>
	<header>
		
		<div class="header-element">
			<img src="img/logo.jpeg" alt="logo">
			<section class="barra-busqueda">	
				<form>
					<input type="text" name="search" id="" placeholder="Buscar...">
				</form>
				<div class="barra-busqueda__resultados"></div>
			</section>
		</div>
		
		<nav>
			<div class="navbar-element">
				<a href="auth/login.php">Login</a>
			</div>
			<div class="navbar-element">
				<a href="auth/register.php">Crear una cuenta</a>
			</div>
		</nav>
	</header>
	<script type="module" src="js/busqueda.js"></script>
</body>
</html>
