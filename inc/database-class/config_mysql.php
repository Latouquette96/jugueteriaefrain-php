<?php
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";

class ConfiguracionMySQL extends ConexionMySQL {
    //Atributos
    protected $sentencia;

    /**
    * Constructor de configuracion
    */
    function ConfiguracionMySQL($user, $pass){
        parent:: __construct($user, $pass);
        $this->sentencia = null;
    }

    /*
    * Inicia la conexion y recupera el registro que coincide con la configuracion requerida.
    */
    protected function recuperar_registro($config){
        //Resultado a retornar
        $to_return = null;
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT * FROM config WHERE c_name=(?);";
        $this->sentencia = $this->mysql->prepare($sql);
        $this->sentencia->bind_param("s", $config);

        $this->sentencia->execute();
        if ($this->sentencia->bind_result($config_name, $config_descrip)){
            $this->sentencia->fetch();
            $to_return = $config_descrip;
        }

        return $to_return;
    }

    /**
     * Recupera el directorio principal de los catalogos CSV dentro del servidor.
     */
    function get_directory_catalog(){
        return $this->recuperar_registro("dir-catalogo");
    }

    /**
     * Recupera el directorio principal de las imagenes de productos dentro del servidor.
     */
    function get_directory_imagen(){
        return $this->recuperar_registro("dir-imagenes");
    }
}
?>