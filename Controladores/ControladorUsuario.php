<?php


class ControladorUsuario {
    
    public function registraUsuario(){
        if($_GET['action']=='usuario'){         
            $db = new DataBase();
            $db->conectar();
            $parametros = json_decode(file_get_contents('php://input'));
            $parametros = (array) $parametros;
            
            if(!empty($parametros)){
                if(isset($parametros['usuario'])&&isset($parametros['pass'])){
                    $usuario = new Usuario($parametros['usuario'], $parametros['pass']);
                    if($usuario->registrar()){
                     $this->response(200,"success","new record added");
                    } else 
                    {
                        $this->response(500,"error","Existe un problema en el servidor");
                    }   
                }
                else {
                    $this->response(422,"error","La propiedad no ha sido definida");
                }
            }
            else{
                $this->response(422,"error","Nada que agregar, checar JSON");  
            }
        }else{
            $this->response(400);
        }          
    }     
    
    function response($code=200, $status="", $message="") {
        http_response_code($code);
        if( !empty($status) && !empty($message) ){
            $response = array("status" => $status ,"message"=>$message);  
            echo json_encode($response,JSON_PRETTY_PRINT);    
        }  
    }
}
