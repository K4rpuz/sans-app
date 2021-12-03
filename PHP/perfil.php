<?php
	session_start();
	$user = $_SESSION['user'];
	if( empty($user) ) header("location: ../PHP/auth.php",true);
	require_once 'header.php';
	require 'connect.php';
	$pdo =connect($dbHost, $db, $dbPort, $dbUser, $dbPass);
?>
	
		
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
						$rol = $_SESSION['rol'];
						$query = "SELECT correo FROM usuario WHERE rol='$rol'";
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
			<h3>Movimiento</h3>			
			<div class="perfil-info perfil-info--movimiento">
				<div class="perfil-compras">
							<p><span class="bold">Compras</span></p>
							<p> <?php echo $user ?> </p>
						<form action="">
							<button type="submit" class="boton-rojo boton">Historial de compra</button>
						</form>
				</div>
				<div class="perfil-ventas">
							<p><span class="bold">Ventas</span></p>
							<p> <?php
								echo $correo;	
								?>
							</p>
						<form action="">
							<button type="submit" class="boton-amarillo boton">Historial de venta</button>
						</form>
				</div>		
			</div>
	</div>
		<div class="perfil-layout">
			<h3>Opciones</h3>
			<div class="perfil-opciones">			
				<button class="boton-cambio-password boton-azul boton">Cambiar contraseña</button>
				<button class="boton-eliminar-cuenta boton-rojo boton">ELIMINAR CUENTA</button>
			</div>	
		</div>
		</section>	
		<script type="module" src="../JS/busqueda.js"></script>
</body>
</html>
