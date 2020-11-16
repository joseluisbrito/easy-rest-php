### Easy Rest PHP

Easy Rest PHP es una clase php _ base _ para implementar un servicio REST usando PHP7 sin necesidad de instalar ningun framework

#### Ejemplo básico de uso:

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



