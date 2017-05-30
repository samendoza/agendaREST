<?php
    class Usuario{
        private $usuario;
        private $pass;
        private $img="";

        function __construct($usuario, $pass){
            $this->usuario = $usuario;
            $this->pass = $pass;
        }

        /*************************************************************************************************************************
        *Funcion iniciarSesion() verifica si un nombre de usuario y contraseña estan almacenados en la base y ademas coinciden
        *retorno: boolean true->inicio exitoso/usuario y contraseña corresponden   
        *         false->El usuario y/o la contraseña no coinciden
        /*************************************************************************************************************************/
        public function iniciarSesion(){
            $db = new DataBase();
            $db->conectar();
            
            $query = "call iniSesion('".$this->usuario."' , '".$this->pass."')";
            $result = $db->consulta($query);

            //Si hay un registro, el usuario y la contraseña existen
            if($result->num_rows > 0) {
               
                //recupero la direccion de la imagen del usuario para ponersela en su perfil
                while($row = mysqli_fetch_assoc($result)){
                     $this->img = $row['fotoUsuario'];
                 }
             
                $db->desconectar();
                return true;
            }
            $db->desconectar();
            return false;
            
        }

        /*************************************************************************************************************************
        *Funcion registrar() registra un nuevo usuario en la base de datos
        *retorno: boolean true->registro exitoso  
        *         false->falla al momento de hacer el registro en la bd
        /*************************************************************************************************************************/
        public function registrar(){
            $db = new DataBase();
            $db->conectar();
            $query = "insert into usuarios (id, pass, fotoUsuario) values('".$this->usuario."','".$this->pass."','".$this->img."')";

            if($db->consulta($query)) {
                $db->desconectar();
                return true;
            }

            $db->desconectar();
            return false;
        }

        /*************************************************************************************************************************
        *Funcion estaRegistrado(): Devuelve true o false dependiendo si el nombre de usuario ya se usa en otro registro
        *retorno: boolean true->Nombre de usuario ocupado
        *         false->Nombre de usuario disponible
        /*************************************************************************************************************************/
        public function estaRegistrado(){
            $db = new DataBase();
            $db->conectar();
            
            $query = "Select * from registroUsuario where usuario = '".$this->usuario."'";
            $result = $db->consulta($query);

            //el nombre de usuario ya existe
            if($result->num_rows > 0) {
                $db->desconectar();
                return true;
            }
    
            //el nombre de usuario esta disponible
            $db->desconectar();
            return false;
        }

        /*************************************************************************************************************************
        *Funcion actualizarPass(): Cambia la contraseña del usuario y devuelve true o false dependiendo el cambio fue exitoso
        *retorno: boolean true-> cambio exitoso
        *         false->error al cambiar la contraseña
        /*************************************************************************************************************************/
        public function actualizarPass($passNuevo){
            $db = new DataBase();
            $db->conectar();
            $query = "update registrousuario set pass = '".$passNuevo."' where usuario =  '".$this->usuario."'";

            //cambio exitoso
            if($db->consulta($query)) {
                $db->desconectar();
                return true;
            }
    
            //error al cambiar la contraseña
            $db->desconectar();
            return false;
        }

        public function actualizarPerfil($nombre, $apPat, $apMat, $sexo, $estado, $fechaNac, $php, $java, $jquery){
            $db = new DataBase();
            $db->conectar();
            $query = "update usuarios set nombre = '".$nombre."', "
                    . "apPat = '".$apPat."', "
                    . "apMat = '".$apMat."', "
                    . "sexo = '".$sexo."', "
                    . "estado = '".$estado."', "
                    . "fechaNac = '".$fechaNac."', " 
                    . "php = ".$php.", " 
                    . "java = ".$java.", "
                    . "jquery = ".$jquery
                    . " where id =  '".$this->usuario."'";
            echo $query;
            //cambio exitoso
            if($db->consulta($query)) {
                $db->desconectar();
                return true;
            }
    
            //error al cambiar la contraseña
            $db->desconectar();
            return false;
        }
        
         /*************************************************************************************************************************
        *Funcion estaRegistrado(): Devuelve true o false dependiendo si el nombre de usuario ya se usa en otro registro
        *retorno: boolean true->Nombre de usuario ocupado
        *         false->Nombre de usuario disponible
        /*************************************************************************************************************************/
        public function getDatos(){
            $db = new DataBase();
            $db->conectar();
            
            $query = "Select * from usuarios where id = '".$this->usuario."'";
           // echo $query;
            $result = $db->consulta($query);
            $res = array();
            while($row = mysqli_fetch_assoc($result)){
               $res[] = $row;
            }
            
            $db->desconectar();
            return $res;
        }
        
        public function setImg($img){
            $this->img = $img;
        }

        public function getImg(){
            return $this->img;
        }

        public function getUsuario(){
            return $this->usuario;
        }  
    }
?>