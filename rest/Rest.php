<?php
### Easy Rest Api - Developed by José Luis Brito - May 2020 - GNU GPL v2 ###
### Please, use, reuse and improve ! ###

namespace Rest;

use \Exception;

class Rest {

      //Http vars:
      private $reqMethod;
      private $reqConType;
      private $allowedMethod;
      private $reqBody;

      // Response codes:
      public $HTTP_OK                 = 200;
      public $HTTP_CREATED            = 201;
      public $HTTP_NOT_MODIFIED       = 304;
      public $HTTP_BAD_REQUEST        = 400;
      public $HTTP_UNAUTHORIZED       = 401;
      public $HTTP_FORBIDDEN          = 403;
      public $HTTP_NOT_FOUND          = 404;
      public $HTTP_METHOD_NOT_ALLOWED = 405;
      public $HTTP_NOT_ACCEPTABLE     = 406;
      public $HTTP_INTERNAL_ERROR     = 500;

      private $conn;
      private $token;

      public function __construct($allowed_method) {
          try {
              // Chequear metodo si es GET, POST, PUT, DELETE, etc:
              $this->reqMethod = $_SERVER['REQUEST_METHOD'];

              // Asignar el ContentType:
              $headers = getallheaders();

              if(!isset($headers['Content-Type']) && isset($headers['content-type']))
              {
                $this->reqConType = $headers['content-type'];
              } else {
                $this->reqConType = $headers['Content-Type'];
              }

              if(isset($headers["authorization"])) {
                $this->token = str_replace("Bearer ", "", $headers["authorization"]);
              }
              if(isset($headers["Authorization"])) {
                $this->token = str_replace("Bearer ", "", $headers["Authorization"]);
              }

              // Asignar el methodo valido:
              $this->allowedMethod = $allowed_method;

              // Compruebo el metodo y el ContentType:
              $this->checkAllowedMethod();
              $this->checkAllowedContentType();

              $this->reqBody = json_decode(file_get_contents('php://input'));

          } catch (\Throwable $t) {
              throw $t;
          }
      }

      public function getBody() {
            return $this->reqBody;
      }

      public function getToken() {
        return $this->token;
      }

      public function response($data, $respCode) {
          try {
            $this->responseCode($respCode);
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            if(is_array($data)) {
                echo json_encode($data,JSON_UNESCAPED_SLASHES);
            }
            else {
                echo json_encode($data, JSON_UNESCAPED_SLASHES);
            }
            $this->conn = null; //"Cierro" la conexión
            exit(); //Termino la ejecución
          } catch (\Throwable $t) {
            throw $t;
          }
      }

      public function responseCode($respCode) {
          switch ($respCode) {
              case '201':
                  header("HTTP/1.1 201 Created");
                  break;
              case '304':
                  header("HTTP/1.1 304 Not Modified");
                  break;
              case '400':
                  header("HTTP/1.1 400 Bad Request");
                  break;
              case '401':
                  header("HTTP/1.1 401 Unauthorized");
                  break;
              case '403':
                  header("HTTP/1.1 403 Forbidden");
                  break;
              case '404':
                  header("HTTP/1.1 404 Not Found");
                  break;
              case '405':
                  header("HTTP/1.1 405 Method Not Allowed");
                  break;
              case '406':
                  header("HTTP/1.1 406 Not Acceptable");
                  break;
              case '500':
                  header("HTTP/1.1 500 Internal Server Error");
                  break;
              default:
                  header("HTTP/1.1 200 OK");
                  break;
          }

      }

      public function checkAllowedMethod() {
          if($this->reqMethod != $this->allowedMethod) {
              $this->response("Metodo no permitido", $this->HTTP_METHOD_NOT_ALLOWED);
          }
      }

      public function checkAllowedContentType() {
          if($this->reqConType != 'application/json') {
              $this->response("Petición no aceptable", $this->HTTP_NOT_ACCEPTABLE);
          }
      }

}
