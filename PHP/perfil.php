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
						else{
							echo '<a href="perfil.php">'.$user.'</a>';	
							echo '<a href="auth.php">Logout</a>';	
						} 
				?>
			</div>
		</nav>
	</header>

	<section class="perfil">
		<div class="perfil-nombre">
			<p><?php echo $_SESSION['user'] ?> </p>
		</div>
		<?php
			$query = "SELECT * FROM usuario";
			$pdo =connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
			$pdo->query($query);
		?>
		<div class="perfil-correo">
			<p> <?php
				
			?> </p>
		</div>
	</section>
	
	<script type="module" src="../JS/busqueda.js"></script>
</body>
</html>
