<?php require_once 'head.php'; ?>
<body>
	<header>
		
		<div class="header-element">
			<a href="index.php" class="logo-img">
				<img src="../IMG/logo.jpeg" alt="logo">
			</a>
			<section class="barra-busqueda">	
				<form action="search.php" method="GET">
					<select name="tipo" id="">
						<option value="productos">Productos</option>
						<option value="usuarios">Usuarios</option>
						<option value="categorias">Categoria</option>
					</select>
					<input type="text" name="busqueda" id="" placeholder="Buscar..." autocomplete="off">
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
							$result = $pdo->query("SELECT count(*) FROM carrito WHERE rol_usuario = '".$_SESSION['rol']."'")->fetch(PDO::FETCH_ASSOC);
							echo '<a href="carrito.php">Carrito ('.$result['count'].')</a>';	
							echo '<a href="perfil.php" class="nombre-usuario">'.$user.'</a>';	
							echo '<a href="auth.php">Logout</a>';	
						} 
				?>
			</div>
		</nav>
	</header>
