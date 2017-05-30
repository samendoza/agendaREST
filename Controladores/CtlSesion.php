<?php
    require '../modelo/Database.php';
    require '../modelo/Usuario.php';
   
    if($_POST['usuario']&& $_POST['pass']){
        
        //Obtener datos del formulario
        $user = $_POST['usuario'];
        $pass = $_POST['pass'];

        //verificacion de usuario y contraseña
        $usuario = new Usuario($user, $pass);
        if($usuario->iniciarSesion()){
            session_start();
            $_SESSION['usuario'] = $usuario->getUsuario();
            $_SESSION['foto'] = $usuario->getImg();
            $_SESSION['logged_in'] = true;
            echo "1"; //Caso 1: Sesion iniciada correctamente, se redirige al contenido
        }
        else
            echo "2";//Caso 2: Error en usuario y/o contraseña
    }
    else
        echo "3";//Caso 3: Algun campo vacio
?>