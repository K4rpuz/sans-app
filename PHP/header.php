<?php require_once 'head.php'; ?>
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

						$user = isset($_SESSION['user'])?$_SESSION['user']:null;
						if( empty($user) ) echo '<a href="auth.php">Iniciar Sesión</a>';
						else{
							echo '<a href="carrito.php">Carrito</a>';	
							echo '<a href="perfil.php" class="nombre-usuario">'.$user.'</a>';	
							echo '<a href="auth.php">Logout</a>';	
						} 
				?>
			</div>
		</nav>
	</header>
