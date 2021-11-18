<?php
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";

class DisponibilidadMySQL extends ConexionMySQL {
    //Atributos
    protected $sentencia;

    /**
    * Constructor de condicion_mysql
    */
    function DisponibilidadMySQL($user, $pass){
        parent:: __construct($user, $pass);
        $this->sentencia = null;
    }

    /*
    * Recupera, en un arreglo, todas las disponibilidad de productos, siendo I la clave (la misma id) y 
    * el valor array[I] el nombre de la condicion.
    */
    function get_array_disponibilidad(){
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT * FROM disponibilidad ORDER BY di_name;";
        $this->sentencia = $this->mysql->prepare($sql);

        $array_data = array();
        //Para la clave 0, el valor es un producto vacio.
        $array_data[0] = array("0", "Seleccione disponibilidad del producto");

        $this->sentencia->execute();
        //Si hay datos que recuperar.
        if ($this->sentencia->bind_result($id, $name)){
            //Si hay elementos que recuperar.
             while ($this->sentencia->fetch()){
                 //Almacenar el elemento en el arreglo.
                $array_data[$id] = array($id, $name);
            }
        }
        return $array_data;
    }
}
?>