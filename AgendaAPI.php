<?php
include 'DB/DataBase.php';
include 'Controladores/ControladorUsuario.php';
include 'Modelo/Usuario.php';

class AgendaAPI {
    public function API(){
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            echo 'GET';
           
            break;     
        case 'POST'://inserta
            echo 'POST';
            $this->registrarUsuario();
            break;                
        case 'PUT'://actualiza
            echo 'PUT';
            
            break;      
        case 'DELETE'://elimina
            echo 'DELETE';
            
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
    }
    
    function response($code=200, $status="", $message="") {
        http_response_code($code);
        if( !empty($status) && !empty($message) ){
            $response = array("status" => $status ,"message"=>$message);  
            echo json_encode($response,JSON_PRETTY_PRINT);    
        }  
    }
    
    
    public function registrarUsuario(){
       $ctrlUsuario = new ControladorUsuario();
       $ctrlUsuario->registraUsuario();
    }
}
