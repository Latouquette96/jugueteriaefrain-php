<?php
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";

class MarcaMySQL extends ConexionMySQL {
    //Atributos
    protected $sentencia;

    /**
    * Constructor de marca_mysql
    */
    function MarcaMySQL($user, $pass){
        parent:: __construct($user, $pass);
        $this->sentencia = null;
    }

    /*
    * Recupera, en un arreglo, todas las Marca de productos.
    */
    function get_array_marca(){
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT p_brand FROM productos ORDER BY p_brand;";
        $this->sentencia = $this->mysql->prepare($sql);

        $array_data = array();
        //Para la clave 0, el valor es un producto vacio.
        $array_data[0] = "Seleccione marca del producto";
        $pos = 1;

        $this->sentencia->execute();
        //Si hay datos que recuperar.
        if ($this->sentencia->bind_result($name)){
            //Si hay elementos que recuperar.
             while ($this->sentencia->fetch()){
                 //Almacenar el elemento en el arreglo.
                $array_data[$pos] = $name;
                $pos = $pos + 1;
            }
        }
        return $array_data;
    }
}
?>