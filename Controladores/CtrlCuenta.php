<?php
    require '../modelo/Database.php';
    require '../modelo/Usuario.php';

    session_start();
    if(isset($_POST['peticion'])){
        if($_POST['peticion']=='agregar'&&$_POST['usuarioR']&& $_POST['passR'] && $_POST['pass2R']){
            $user = $_POST['usuarioR'];
            $pass = $_POST['passR'];
            $pass2 = $_POST['pass2R'];

            if($pass == $pass2){
                $usuario = new Usuario($user, $pass);
                $nombreArch = $_POST['usuarioR'].".".explode(".",$_FILES['foto']['name'])[1];
                //echo $nombreArch;
                $usuario->setImg("img/fotosUsuario/".$nombreArch);

                //Caso 1: El usuario se registro con exito
                //Caso 2: Hay un problema interno al hacer el registro
                //Caso 3: Las contraseñas no coinciden

                if($usuario->registrar()){
                    subirFoto($nombreArch);
                    echo "1";
                }
                else
                    echo "2";
            }
            else
                echo "3";
        }
        else if($_POST['peticion']=='modificar'&&$_POST['pass']&& $_POST['passN'] && $_POST['pass2N']){
            $passN = $_POST['passN'];
            $pass2N = $_POST['pass2N'];
            $usuario = new Usuario($_SESSION['usuario'], $_POST['pass']);
                
            if($usuario->iniciarSesion()){
                if($passN==$pass2N){
                    if($usuario->actualizarPass($passN)){
                        if($_FILES['foto']['name']!=''){
                            $nombreArch = $_SESSION['usuario'].".".explode(".",$_FILES['foto']['name'])[1];
                            subirFoto($nombreArch);
                        }
                        echo "1"; //Caso 1)Cambio exitoso de contraseña y actualizacion de la informacion de perfil
                    }
                    else{
                        echo "2"; //Caso 2)No se pudo hacer el cambio de contraseña
                    }
                }
                else
                    echo "3";//Caso 3)Error, contraseña nueva no coincide
            }
            else
                echo "4";//Caso 4) Datos (contraseña) no coincide con la actual
        }
    }

    function eliminarFoto($contacto){
        $ruta = $contacto->getDirImagen();
        unlink("../".$ruta);
           
    }

    function subirFoto($nombreArch){
        if(!is_dir("../img/fotosUsuario")) 
            mkdir("../img/fotosUsuario", 0777);
        
        //comprobamos si el archivo ha subido
        if ($_FILES['foto']['name'] && move_uploaded_file($_FILES['foto']['tmp_name'],"../img/fotosUsuario/".$nombreArch)){
            sleep(3);//retrasamos la petición 3 segundos
        }
    }    
?>