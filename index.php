<?php

	### Ejemplo de uso de Easy Rest Api

	require 'vendor/autoload.php';

	use Rest\Rest;
	// Creo una instancia de la api:
	$api = new Rest("POST");

	$message = $api->getBody()->message;
	
	if(isset($message) && $message !== "") {
	        $api->response("Mensaje recibido correctamente: ".$message, $api->HTTP_OK);
	}
	else {
        	$api->response("Solicitud incorrecta!", $api->HTTP_BAD_REQUEST);
	}


?>
