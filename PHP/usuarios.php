<?php
	require_once "header.php";
	require_once 'request_processing.php';
	if( empty($_GET['rol']) ) {
		header('Location: perfil.php',true);
	}
	$rol = $_GET['rol'];
	$nombre = "";
	$correo = "";
	$nacimiento = "";
	foreach($pdo->query("SELECT usuario, correo, nacimiento FROM usuario WHERE rol='$rol'") as $fila){
		$nombre = $fila['usuario'];
		$correo = $fila['correo'];
		$nacimiento = $fila['nacimiento'];
	}
	$cantidad_vendida = "";
	$calificacion_promedio = "";
	$ganancia_total = "";

	if(isset($_GET['rol'])){
		$result = $pdo->query("SELECT * FROM usuario_info WHERE rol = '".$_GET['rol']."'")->fetch(PDO::FETCH_ASSOC);
		if(!$result) die('<div class="alerta">usuario no encontrado</div>');


		$cantidad_vendida = $result['cantidad_vendida'];
		$calificacion_promedio = $result['calificacion_promedio'];
		$ganancia_total = $result['ganancias_totales'];
			
		?>

<div class="container">
	<section class="perfil-usuario ">
		<h3> <?php
			echo $nombre;	
		?> </h3>
		<div class="usuario-info-personal">
			<div class="usuario-info-personal-dato">
				<p class="usuario-info-personal-dato-etiqueta">Rol</p>
				<p> <?php
					echo $rol;
				?> </p>
			</div>
			<div class="usuario-info-personal-dato">
				<img src="../IMG/mail-box.png" alt="Correo:" class="usuario-info-icon">
				<p> <?php
					echo $correo;
				?> </p>
			</div>
			<div class="usuario-info-personal-dato">
				<img src="../IMG/birthday-cake.png" class="usuario-info-icon" alt="Nacimiento:">
				<p> <?php
					echo $nacimiento;
				?> </p>
			</div>
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
			
		</div>
		<div class="usuario-info-movimientos">
		</div>
	</section>
</div>


<?php
	
		$results = $pdo->query("SELECT * FROM producto_info WHERE vendedor= '".$_GET['rol']."'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		if(!$result) echo "Este usuario no tiene productos en venta";
		
		else{
			
		?>	

		<div class="contenedor-productos-perfil">
<?php
	
			while($result){			
				?>
				<div class="contenedor-producto contenedor-producto--mini">	
			<div class="producto-cabecera">
				<a href="productos.php?sku=<?php
					echo $result['id'];
				?>">
				<h3> <?php
					echo $result['nombre'];
?> </h3> </a>
				<div class="boleta-calificacion">
					<?php
						$calificacion = $result['calificacion_promedio'];
						for( $i = 0; $i < $calificacion; ++$i ){
							echo '<img src="../IMG/estrella.png"class="img-calificacion"></img>';
						}

					?>
				</div>
							</div>
			
			<p class="producto-descripcion"> <?php
				echo $result['descripcion'];
			?> </p>
			<p class="producto-precio">
					Precio:
					<?php
						echo $result['precio'];
					?>
					$
			</p>
			<p class="producto-categoria">
			
				Categoria: 
				<?php
					echo $result['categoria'];
				?>
			</p>

			<p class="producto-cantidad-vendida">
				Se ha vendido: 
				<?php
					echo $result['cantidad_vendida'];
				?>
					veces.
			</p>

			</div>
				<?php
					
				/*echo '<div class="">';
				foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
				}
				echo '</div><br>';*/
				$result = $results->fetch(PDO::FETCH_ASSOC);
			
			}			
		}
			
		?>

		</div>
		<?php
			

		
	}else{
		die("No se ha recibido el rol del usuario"); 	
	}
?>


	
<?php
	require_once "footer.php";
?>
