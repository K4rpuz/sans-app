<?php

function getParameter($parameter){
	if(isset($_POST[$parameter])){
			return $_POST[$parameter];
	} return false;
}

function response($answer){
	echo json_encode(array( 'ANSWER' => $answer ));
}

function responseAndDie($answer){
	response($answer);
	die();
}

function showAndDie($json){
	echo json_encode($json);
	die();
}

function isValid($value, $type){
	/* TODO PROGRAMAR VALIDACIONES */
	return;
}

function loadForm($parameters){
	$connection = require_once './connect.php';
	$form = array();	
	foreach( $parameters as $key => $type ){
		$parameter = getParameter($key);		
		$key = strtoupper($key);
		if(!$parameter) {
			responseAndDie("NOT_$key");
		}
		$form[$key] = $parameter;
	}
	return $form;
}


?>
