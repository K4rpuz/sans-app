<?php
require_once "header.php";

if(isset($_GET['busqueda'])){
	$results = null;

	try{
		if($_GET['tipo'] == "productos"){
			$results = $pdo->query("SELECT * FROM producto_info WHERE LOWER(nombre) LIKE '%".strtolower($_GET['busqueda'])."%'");
		}else if($_GET['tipo'] == "categorias"){
			$results = $pdo->query("SELECT * FROM producto_info WHERE LOWER(categoria) LIKE '%".strtolower($_GET['busqueda'])."%'");
		}else if($_GET['tipo'] == "usuarios"){
			$results = $pdo->query("SELECT * FROM usuario_info WHERE LOWER(usuario) LIKE '%".strtolower($_GET['busqueda'])."%'");
		}
	}catch(PDOException $e){
		echo $e->getMessage();
	}
	if(!$results){
		foreach ($_GET as $key => $value) {
			echo $key . ': ' . $value . '<br>';
		}
		die();
	}

	$result = $results->fetch(PDO::FETCH_ASSOC);
	echo '<div class="resultados-busqueda">';
	if(!$result) echo "No se han encontrado coincidencias";
	while($result){
		echo "<div class='contenedor-producto contenedor-producto--mini'>";
		
			
		?>
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
		/*foreach ($result as $key => $value) {
			echo $key.": ".$value."<br>";
					}*/
		$result = $results->fetch(PDO::FETCH_ASSOC);

	}
	echo '</div>';
}


require_once "footer.php";
?>
