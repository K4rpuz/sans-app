<?php
require_once "header.php";

if(isset($_POST['busqueda'])){
	echo json_encode(array(
		'ANSWER' => "OK",
		'Productos' => array('playera', 'taza','mouse')
	));

}

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
	if(!$result) echo "No se han encontrado coincidencias";
	while($result){
		echo "<div class='resultado'>";
		foreach ($result as $key => $value) {
			echo $key.": ".$value."<br>";
		}
		echo "</div><br>";
		$result = $results->fetch(PDO::FETCH_ASSOC);

	}
}


require_once "footer.php";
?>
