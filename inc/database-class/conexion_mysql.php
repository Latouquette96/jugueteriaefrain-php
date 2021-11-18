<?php

class ConexionMySQL{
    //Atributos del objeto
    protected $mysql; //manejador

    /**
     * Constructor de una conection MySQL.
     */
    function __construct($user, $pass){
        //$user = $_POST['usuario'];
        //$pass = $_POST['pass'];
    
        $this->mysql = new mysqli("localhost", $user, $pass, "db_jugueteria_efrain");
        if ($this->mysql->connect_errno) {
            echo "Falló la conexión con MySQL: (".$this->mysql->connect_errno.") ".$this->mysql->connect_error;
        }
    }

    function __destruct(){}
}
?>