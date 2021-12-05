<?php
	require_once 'header.php';
	$user = getOrGoLogin('user')
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
						<a href="#" class="boton-editar-nombre"><img src="../IMG/editar.png" class="boton-perfil" alt=""></a>
					</div>
					<?php
						$rol = $_SESSION['rol'];
						$query = "SELECT correo, nacimiento FROM usuario WHERE rol='$rol'";
						$correo = "";
						$nacimiento = "";
						foreach( $pdo->query($query) as $fila){
							$correo = $fila['correo'];
							$nacimiento = $fila['nacimiento'];
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
						<a href="#" class="boton-editar-correo"><img src="../IMG/editar.png" class="boton-perfil" alt=""></a>
					</div>	
					<div class="perfil-nacimiento">
						<p><span class="bold">Nacimiento</span></p>
						<p class="p-nacimiento"> <?php
							echo $nacimiento;	
							?>
						</p>
						<form action="perfil_requests.php" class="form-cambio-nacimiento hide">
							<input type="hidden" name="request" value="cambio-nacimiento">
							<input type="date" name="nacimiento" id="" placeholder="30/10/2000">
						</form>
						<a href="#" class="boton-editar-nacimiento"><img src="../IMG/editar.png" class="boton-perfil" alt=""></a>
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
							echo $cantidadCompras;
						foreach($pdo->query("SELECT count(*), count(calificacion) AS calificadas FROM boleta WHERE rol_comprador='$rol'") as $fila){
							$cantidadCompras = $fila['count'];
							$comprasSinCalificar = $cantidadCompras-$fila['calificadas'];
						}
							

						?></p>
						<form action="historial.php" method="GET">
							<input type="hidden" name="request" value="compras">
							<button type="submit" class="boton-rojo boton boton-historial-compra">Historial de compra</button>
							<div class="notificacion notificacion--comentario">
								<?php
									if($comprasSinCalificar > 0)
									echo "<p class='bold'>$comprasSinCalificar</span>";
								?>
							</div>
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
			<h3>Stats</h3>			
			<div class="perfil-info perfil-info--stats">
			<div class="usuario-info-personal-dato">
				<img src="../IMG/trade.png" class="usuario-info-icon" alt="Vendido">
				<p> <?php
					if($cantidad_vendida == "") echo "No tiene ventas.";
					else echo $cantidad_vendida;
				?> </p>
			</div>
			<div class="usuario-info-personal-dato">
				<img src="../IMG/estrella.png" class="usuario-info-icon" alt="calificacion_promedio">
				<p> <?php
					if($calificacion_promedio == "") echo "No tiene calificaciones.";
					else echo $calificacion_promedio;
				?> </p>
			</div>
			<div class="usuario-info-personal-dato">
				<img src="../IMG/money-bag.png" class="usuario-info-icon" alt="ganancias totales">
				<p> <?php
					if($ganancia_total == "") echo "No tiene ganancias.";
					else echo $ganancia_total . ' $';
				?> </p>
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
