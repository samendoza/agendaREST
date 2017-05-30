<?php


class ControladorUsuario {
    
    public function registraUsuario(){
        if($_GET['action']=='usuario'){         
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
    
    public function actualizaPerfil(){
        if( isset($_GET['action']) && isset($_GET['id']) ){
            if($_GET['action']=='usuario'){
                $param = json_decode( file_get_contents('php://input') );   
                $param = (array)$param;
                if (empty($param)){                        
                    $this->response(422,"error","Nada que agregar, checar el JSON");                        
                }else if(isset($param['nombre'])){ //verificar demas parametros
                    $user = $_GET['id'];
                    echo $user;
                    $usuario = new Usuario($user, '');
                    if($usuario->actualizarPerfil(
                            $param['nombre'],
                            $param['apPat'],
                            $param['apMat'],
                            $param['sexo'],
                            $param['estado'],
                            $param['fechaNac'],
                            $param['php'],
                            $param['java'],
                            $param['jquery'])){
                        $this->response(200,"success","Registro actualizado");  
                    }
                    else{
                        $this->response(500,"error","Error interno al realizar la actualizacion");  
                    }                    
                }else{
                    $this->response(422,"error","La propiedad no está definida");                        
                }     
                exit;
            }
        }
        $this->response(400);
    }
    
    public function obtenerDatos(){
        if($_GET['action']=='usuario'){         
            if(isset($_GET['id'])){//muestra 1 solo registro si es que existiera ID                 
                $usuario = new Usuario($_GET['id'], '');
                $result = $usuario -> getDatos();
                echo json_encode($result,JSON_PRETTY_PRINT);
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
