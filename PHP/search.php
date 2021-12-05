<?php
require_once "header.php";

echo "post";
foreach ($_POST as $key => $value) {
	echo $key . ': ' . $value . '<br>';
}

echo "get";
foreach ($_GET as $key => $value) {
	echo $key . ': ' . $value . '<br>';
}

echo json_encode(array(
	'ANSWER' => "OK",
	'Productos' => array('playera', 'taza','mouse')
));

require_once "footer.php";
?>
