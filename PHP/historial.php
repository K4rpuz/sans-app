<?php
	require_once "header.php";
	require_once 'request_processing.php';
	$rol = getOrGoLogin('rol');

	if(isset($_GET['request'])){
		switch ($_GET['request']) {
			case 'ventas':
				echo '<h1>Historial de ventas</h1><hr><div class="historial_venta">';
				$results = $pdo->query("SELECT * FROM boleta WHERE rol_vendedor= '$rol'");
				$result = $results->fetch(PDO::FETCH_ASSOC);
				if(!$result) echo "No haz vendido ningun producto";
				
				else{
					while($result){
						echo '<div class="boleta">';
						foreach($result as $key => $value){
							echo $key.": ".$value."<br>";
						}
						echo '</div><br>';
						$result = $results->fetch(PDO::FETCH_ASSOC);
					}
						
				}
				break;
			case 'compras':
				echo '<h1>Historial de compras</h1><hr><div class="historial_compra">';
				$results = $pdo->query("SELECT * FROM boleta WHERE rol_comprador= '$rol'");
				$result = $results->fetch(PDO::FETCH_ASSOC);
				if(!$result) echo "No haz comprado ningun producto";
				
				else{
					while($result){
						echo '<div class="boleta">';
						foreach($result as $key => $value){
							echo $key.": ".$value."<br>";
						}
						if($result['calificacion']){
							echo '<a href="historial.php?request=calificar&id='.$result['id'].'">Editar calificacion</a>';
						}else{
							echo '<a class="bold" href="historial.php?request=calificar&id='.$result['id'].'">Calificar</a>';
						}
						echo '</div><br>';
						$result = $results->fetch(PDO::FETCH_ASSOC);
					}
						
				}
				break;
			case 'calificar':
				if(!isset($_GET['id'])) die("No se recibio el id de la boleta");
				$result = $pdo->query("SELECT id,rol_comprador,calificacion,comentario FROM boleta WHERE rol_comprador='$rol' AND id = ".$_GET['id'])->fetch(PDO::FETCH_ASSOC);
				if(!$result) die('<div class="alerta">Boleta no encontrada</div>');

				?>
				<h1>Calificar</h1><hr>
				<form action="productos.php" method="post" id="comentario_form">
					<input type="hidden" name="action" value="add_calification">
					<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
					<input type="range" name="calificacion" list="puntajes" min="1" max="5" step="1" value="<?php echo $result['calificacion']|0; ?>">
					<label for="comentario_producto">Comentario acerca de su compra</label>
					<textarea name="comentario" id="comentario_producto" cols="30" rows="10" form="comentario_form"><?php echo $result['comentario']; ?></textarea>
					<input type="submit" value="Enviar">
					<a href="historial.php?request=compras">Cancelar</a>
				</form>
				<?php
				break;
		}

	}
	

	require_once "footer.php";
?>