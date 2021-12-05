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
		$results = $pdo->query("SELECT * FROM producto_info WHERE vendedor= '".$_GET['rol']."'");
		$result = $results->fetch(PDO::FETCH_ASSOC);
		if(!$result) echo "Este usuario no tiene productos en venta";
		
		else{
			while($result){
				$result = false;
				/*echo '<div class="comment">';
				foreach($result as $key => $value){
					echo $key.": ".$value."<br>";
				}
				echo '</div><br>';
				$result = $results->fetch(PDO::FETCH_ASSOC);
				*/
			}
				
		}


		
	}else{
		die("No se ha recibido el rol del usuario"); 	
	}
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
	require_once "footer.php";
?>
