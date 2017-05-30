<?php
    class Contacto{

        private $idContacto;
        private $nombre;
        private $tel;
        private $cel;
        private $direccion;
        private $email;
        private $dirImagen;

        private $categoria;
        
        /*************************************************************************************************************************
        *Metodo contructor: Inicializa al contacto de diferentes formas, de acuerdo a la peticion que tenga
        *peticion agregar-> inicializa todos los atributos para despues almacernarlos en la base de datos
        *peticion buscar -> Inicializa solo algun campo de contacto, de acuerdo si se busca por nombre, email o cel
        *peticion eliminar -> Inicializa solo el id de contacto para posteriormente eliminarlo de la base de datos
        /*************************************************************************************************************************/
        function __construct($arreglo){
            if($arreglo['peticion'] == "agregar"){
                $this->nombre =  $arreglo['nombre'];
                $this->tel = $arreglo['tel'];
                $this->cel =  $arreglo['cel'];
                $this->direccion =  $arreglo['dir'];
                $this->email =  $arreglo['email'];   
            }
            else if($arreglo['peticion'] == "buscar"){
                $this->categoria = $arreglo['categoria'];
                
                switch($arreglo['categoria']){
                    case "nombre":
                        $this->nombre =  $arreglo['busqueda'];
                        break;
                    case "email":
                        $this->email =  $arreglo['busqueda'];
                        break;
                    case "cel":
                        $this->cel =  $arreglo['busqueda'];
                        break;
                }
            }  
            else if($arreglo['peticion'] == "eliminar"){
                $this->idContacto = $arreglo['valor'];
            }
        }

        /*************************************************************************************************************************
        *Metodo busqueda: realiza una busqueda de acuerdo a un criterio en la tabla contactos
        *Parametros: $usuario-> id del usuario actual al que se le va a asociar el nuevo contacto (llave foranea)
        *Retorno: String que contiene una tabla en html que despliega los resultados de la busqueda
        /*************************************************************************************************************************/
        public function busqueda($usuario){
            $db = new DataBase();
            $db->conectar();
            
            switch($this->categoria){
                    case "nombre":
                        $query = "Select * from contacto c, registroUsuario r where c.idUsuario = r.usuario ".
                                 "and  idUsuario = '".$usuario."' and nombre like '%".$this->nombre."%'";
                        break;
                    case "email":
                        $query = "Select * from contacto c, registroUsuario r where c.idUsuario = r.usuario ".
                                 "and  idUsuario = '".$usuario."' and email like '%".$this->email."%'";
                        break;
                    case "cel":
                        $query = "Select * from contacto c, registroUsuario r where c.idUsuario = r.usuario ".
                                 "and  idUsuario = '".$usuario."' and cel like '%".$this->cel."%'";
                        break;
            }

            $result = $db->consulta($query);
            $resp = "<table><tr><td>Nombre</td><td>Correo</td><td>Tel fijo</td><td>Celular</td><td>Dirección</td><td>Foto</td><td>Eliminar</td></tr>";

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $resp .= "<tr>";
                    $resp .= "<td>".$row['nombre']."</td>";
                    $resp .= "<td>".$row['email']."</td>";
                    $resp .= "<td>".$row['tel']."</td>";
                    $resp .= "<td>".$row['cel']."</td>";
                    $resp .= "<td>".$row['direccion']."</td>";
                    $resp .= "<td class='filaCont'><img style = 'height: 100px; width: 100px' src='".$row['fotoContacto']."'></img> </td>";
                    $resp .= "<td><button onclick='eliminar(this)' value=".$row['idContacto']." ><img  style = ' height: 20px; width: 20px; ' src='img/eliminar.png'></img></button></td>";
                    $resp .= "</tr>";
                }
                 $resp .= "</table>";
            }
            else
                $resp = "No hay resultados disponibles para esta busqueda";

            $db->desconectar();
            return $resp;
        }

        /*************************************************************************************************************************
        *Metodo eliminar: elimina un contacto de la bd
        *Retorno: boolean true-> eliminacion exitosa /   false->Error en la eliminacion 
        /*************************************************************************************************************************/
        public function eliminar(){
            $db = new DataBase();
            $db->conectar();
            $query = "select fotoContacto from contacto where idContacto = '".$this->idContacto."'";
            $result = $db->consulta($query);

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result))
                    $this->dirImagen = $row['fotoContacto']; 
            }

            $query = "delete from contacto where idContacto = ".$this->idContacto;
            if ($db->consulta($query)) {
                $db->desconectar();
                return true;
            } 
            else {
                $db->desconectar();
                return false;
            }
        }

        public function agregar($idUsuario){
            $db = new DataBase();
            $db->conectar();
            $query = "insert into contacto (idUsuario, nombre, tel, cel, email, direccion,fotoContacto) values ('".$idUsuario."','".$this->nombre."','".$this->tel."','".$this->cel."','".$this->email."','".$this->direccion."','".$this->dirImagen."')";

            if ($db->consulta($query)) {
                $db->desconectar();
                return true;
            } 
            else {
                $db->desconectar();
                return false;
            }

        }

        public function setDirImagen($dirImg){
            $this->dirImagen = "img/fotosContacto/".$dirImg;
        }

        public function getDirImagen(){
            return $this->dirImagen;
        }

        

    }
?>