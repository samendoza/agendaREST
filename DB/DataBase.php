<?php
    class DataBase{
        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $db="prueba4";
        
        private $conn;
        private $result;

        public function conectar() {
         
            $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->db);

            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            //echo "Connected successfully";
            return $this->conn;
        }

        public function consulta($sql){
            $this->result = mysqli_query($this->conn, $sql);
            return $this->result;
        }

        public function desconectar(){
            mysqli_close($this->conn);
           
        }
    }
?>