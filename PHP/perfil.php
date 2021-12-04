<?php
	session_start();
	$user = $_SESSION['user'];
	if( empty($user) ) header("location: ../PHP/auth.php",true);
	require_once 'header.php';
?>
	
		
		<section class="container perfil-container">
			<div class="perfil-layout">
				<h3>Información personal</h3>
				
				<div class="perfil-info">
					<div class="perfil-nombre">
						<p><span class="bold">Nombre</span></p>
						<p class="p-nombre"> <?php echo $user ?> </p>
						<form action="perfil_requests.php" class="form-cambio-nombre hide">
							<input type="hidden" name="request" value="cambio-nombre">
							<input type="text" name="user" id="" placeholder="Papa Francisco">
						</form>
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
						<p class="p-correo"> <?php
							echo $correo;	
							?>
						</p>
						<form action="perfil_requests.php" class="form-cambio-correo hide">
							<input type="hidden" name="request" value="cambio-correo">
							<input type="text" name="correo" id="" placeholder="Papa_Francisco@vaticano.cl">
						</form>
						<a href="#" class="boton-editar-correo"><img src="../IMG/editar.png" alt=""></a>
					</div>	
				</div>
		</div>
			
		<div class="perfil-layout">
			<h3>Movimiento</h3>			
			<div class="perfil-info perfil-info--movimiento">
				<div class="perfil-compras">
							<p><span class="bold">Compras</span></p>
							<p> <?php 
							$cantidadCompras = 0;
						foreach($pdo->query("SELECT count(*) FROM boleta WHERE rol_comprador='$rol'") as $fila){
							$cantidadCompras = $fila['count'];
						}
							echo $cantidadCompras;
					?> </p>
						<form action="historial.php" method="GET">
							<input type="hidden" name="request" value="compras">
							<button type="submit" class="boton-rojo boton boton-historial-compra">Historial de compra</button>
						</form>
				</div>
				<div class="perfil-ventas">
							<p><span class="bold">Ventas</span></p>
							<p> <?php
							$cantidadVentas = 0;
						foreach($pdo->query("SELECT count(*) FROM boleta WHERE rol_vendedor='$rol'") as $fila){
							$cantidadVentas = $fila['count'];
						}
							echo $cantidadVentas;
								?>
							</p>
						<form action="historial.php" method="GET">
							<input type="hidden" name="request" value="ventas">
							<button type="submit" class="boton-amarillo boton boton-historial-venta">Historial de venta</button>
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
			<form action="" class="form-cambio-password hide">
				<input type="password" name="password_actual" id="" placeholder="Contraseña actual">
				<input type="password" name="password_nueva" id="" placeholder="Contraseña nueva">
				<input type="hidden" name="request" value="cambio-password">
				<input type="submit" class="boton boton-azul"value="Cambiar">
			</form>
			<p class="error-cambio-clave error hide"></p>
		</div>
		</section>	
		<script type="module" src="../JS/busqueda.js"></script>
		<script type="module" src="../JS/perfil.js"></script>
</body>
</html>
