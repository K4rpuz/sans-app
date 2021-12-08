<?php require_once 'head.php'; ?>
<body>
	<header>
		
		<div class="header-element">
			<a href="index.php" class="logo-img">
				<img src="../IMG/logo.jpeg" alt="logo">
			</a>
			<section class="barra-busqueda">	
				<form action="search.php" method="GET">
					<div class="barra-contenedor">
						<input type="text" name="busqueda" id="" placeholder="Buscar..." autocomplete="off">
						<select name="tipo" id="">
							<option value="productos">Productos</option>
							<option value="usuarios">Usuarios</option>
							<option value="categorias">Categoria</option>
						</select>
					</div>
					</form>
				<div class="barra-busqueda__resultados"></div>
			</section>
		</div>
		
		<nav>
			<div class="navbar-element categorias">
						<h3>Categorias</h3>
						<div class="categorias-links">
							<?php
								{
								foreach($pdo->query('SELECT nombre FROM categoria') as $row) {
									echo '<a href="search.php?tipo=categorias&busqueda='.$row['nombre'].'">'.$row['nombre'].'</a>';
								}
								}
							?>
		
						</div>
		</div>
			<div class="navbar-element">
				<?php
						session_start();

						$user = isset($_SESSION['user'])?$_SESSION['user']:null;
							
						?>
						
						<?php
						if( empty($user) ) echo '<a href="auth.php">Iniciar Sesión</a>';
						else{
							$result = $pdo->query("SELECT count(*) FROM carrito WHERE rol_usuario = '".$_SESSION['rol']."'")->fetch(PDO::FETCH_ASSOC);
							echo '<a href="carrito.php">Carrito ('.$result['count'].')</a>';	
							echo '<a href="productos.php">Mis productos</a>';	
							echo '<a href="perfil.php" class="nombre-usuario">'.$user.'</a>';	
							echo '<a href="auth.php">Logout</a>';	
						} 
				?>
			</div>
		</nav>
	</header>
