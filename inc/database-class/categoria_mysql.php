<?php
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";

class CategoriaMySQL extends ConexionMySQL {
    //Atributos
    protected $sentencia;

    /**
    * Constructor de buscar producto
    */
    function CategoriaMySQL($user, $pass){
        parent:: __construct($user, $pass);
        $this->sentencia = null;
    }

    /*
    * Recupera todas las categorias existentes y las retorna en un arreglo de strings.
    */
    function get_array_categorias(){
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT cat_name FROM categorias ORDER BY cat_name;";
        $this->sentencia = $this->mysql->prepare($sql);

        return $this->get_array();
    }

    /*
    * Recupera, en un arreglo, todas las categorias de productos, siendo I la clave (la misma id) y 
    * el valor array[I] el nombre de la categoria junto a su subcategoria el valor.
    */
    function get_array_categorias_subcategorias(){
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT cat_id, cat_name, cat_subcat FROM categorias ORDER BY cat_name, cat_subcat;";
        $this->sentencia = $this->mysql->prepare($sql);

        $array_data = array();
        //Para la clave 0, el valor es un producto vacio.
        $array_data[0] = array("0", "Seleccione una categoria -> subcategoria");

        $this->sentencia->execute();
        //Si hay datos que recuperar.
        if ($this->sentencia->bind_result($id, $cat, $subcat)){
            //Si hay elementos que recuperar.
             while ($this->sentencia->fetch()){
                 //Almacenar el elemento en el arreglo.
                $array_data[$id] = array($id, $cat."->".$subcat);
            }
        }
        return $array_data;
    }

    /*
    * Recupera todas las subcategorias/divisiones que tiene una categoria y lo retorna como arreglo.
    */
    function get_array_subcategorias($categ){
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $this->sentencia = null;
        //Consulta a emplear
        $sql = "SELECT DISTINCT cat_subcat FROM categorias WHERE cat_name=(?) ORDER BY cat_subcat;";
        $this->sentencia = $this->mysql->prepare($sql);
        $this->sentencia->bind_param("s", $categ);
        
        return $this->get_array();
    }

    /*
    * Devuelve el arreglo almacenado en la sentencia.
    * Solo util para obtener categorias.
    */
    protected function get_array(){
        $pos = 0;
        $array_data = array();
        $dato = null;
        $this->sentencia->execute();
        //Si hay datos que recuperar.
        if ($this->sentencia->bind_result($dato)){
            //Si hay elementos que recuperar.
             while ($this->sentencia->fetch()){
                 //Almacenar el elemento en el arreglo.
                $array_data[$pos] = $dato;
                $pos = $pos + 1;
            }
        }

        return $array_data;
    }

    /**
     * Recupera y devuelve el identificador para una categoria con determinado nombre y division.
     */
    function get_identificador_category($categ, $subcat){
        $identificador = 0;
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT cat_id FROM categorias WHERE cat_name=(?) and cat_subcat=(?);";
        $this->sentencia = $this->mysql->prepare($sql);
        $this->sentencia->bind_param("ss", $categ, $subcat);
        $this->sentencia->execute();
        
        //Los resultados de la busqueda se almacenaran en $dato.
        if ($this->sentencia->bind_result($dato)){
            //Si hay filas que recorrer, entonces
            if ($this->sentencia->fetch()){
                //Almacenar el identificador obtenido.
                $identificador = (int) $dato;
            }
        }

        return $identificador;
    }

    /**
     * Recupera y devuelve el identificador para una categoria con determinado nombre y division.
     */
    function get_all_identificador_category(){
        $array_id = array();
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT cat_id FROM categorias ORDER BY cat_id;";
        $this->sentencia = $this->mysql->prepare($sql);
        $this->sentencia->execute();
        
        
        //Los resultados de la busqueda se almacenaran en $dato.
        if ($this->sentencia->bind_result($id)){
            //Si hay filas que recorrer, entonces
            while ($this->sentencia->fetch()){
                //Almacenar el identificador obtenido.
                array_push($array_id, $id);
            }
        }
        return $array_id;
    }

    /**
     * Devuelve el nombre de la categoria que engloba tanto a la de facebook como a la de google.
     * $id_reg es el número identificador del registro de categoria.
     */
    function get_name_category_subcategory($id_reg){
        $to_return = "";
        //Consulta a emplear
        $this->mysql = new mysqli("localhost", "Latouquette96", "39925523", "db_jugueteria_efrain");
        $sql = "SELECT DISTINCT cat_name, cat_subcat FROM categorias WHERE cat_id=(?);";
        $this->sentencia = $this->mysql->prepare($sql);
        $this->sentencia->bind_param("i", $id_reg);
        $this->sentencia->execute();
        
        //Los resultados de la busqueda se almacenaran en $dato.
        if ($this->sentencia->bind_result($cat_name, $sub_cat_name)){
            //Si hay filas que recorrer, entonces
            if ($this->sentencia->fetch()){
                //Almacenar el identificador obtenido.
                $to_return = $cat_name."-".$sub_cat_name;
            }
        }

        return $to_return;
    }
}
?>