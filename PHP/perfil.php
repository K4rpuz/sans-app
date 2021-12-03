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
			<a href="index.php" class="logo-img">
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
		<section class="container perfil-container">
			<div class="perfil-layout">
				<h3>Información personal</h3>
				
				<div class="perfil-info">
					<div class="perfil-nombre">
						<p><span class="bold">Nombre</span></p>
						<p> <?php echo $user ?> </p>
						<a href="#" class="boton-editar-nombre"><img src="../IMG/editar.png" alt=""></a>
					</div>
					<?php
						require 'connect.php';
						$rol = $_SESSION['rol'];
						$query = "SELECT correo FROM usuario WHERE rol='$rol'";
						$pdo = connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
						$correo = "";
						foreach( $pdo->query($query) as $fila){
							$correo = $fila['correo'];
						}
					?>
					<div class="perfil-correo">
						<p><span class="bold">Correo</span></p>
						<p> <?php
							echo $correo;	
							?>
						</p>
						<a href="#" class="boton-editar-correo"><img src="../IMG/editar.png" alt=""></a>
					</div>	
				</div>
		</div>
			<div class="perfil-layout">
				<h3>Opciones</h3>
				<div class="perfil-opciones">
					<form action="">
						<button type="submit" class="boton-rojo boton">Historial de compra</button>
					</form>
					<form action="">
						<button type="submit" class="boton-amarillo boton">Historial de venta</button>
					</form>
					<button class="boton_cambio_password boton-azul boton">Cambiar contraseña</button>
				</div>	
			</div>
		</section>	
		<script type="module" src="../JS/busqueda.js"></script>
</body>
</html>
