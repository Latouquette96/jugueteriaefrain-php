<?php
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";      

class ProductoMySQL extends ConexionMySQL {
    //Atributos
    protected $sentencia;

    /**
    * Constructor de buscar producto
    */
    function ProductoMySQL($user, $pass){
        parent:: __construct($user, $pass);
        $this->sentencia = null;
    }

    /**
     * Busca el producto con el parametro dado.
     */
    function new_product($producto){
        //Recupero los valores del producto
        $codebar = $producto->get_codebar();
        $title = $producto->get_title();
        $descrip = $producto->get_description();
        $price = $producto->get_price();
        $cat = $producto->get_id_categoria();
        $disp = $producto->get_id_available();
        $cond = $producto->get_id_condition();
        $link = $producto->get_link_page();
        $linkimage = $producto->get_link_image();
        $linkimageadd = $producto->get_link_additional_image();
        $brand = $producto->get_marca();

        $sql = "INSERT IGNORE productos(p_codebar, p_title, p_descrip, p_price, p_cat, p_condition, p_available, p_link, p_linkimage, p_linkimageextra, p_brand) 
            VALUES ((?), (?), (?), (?), (?), (?), (?), (?), (?), (?), (?));";
        
        $sentencia = $this->mysql->prepare($sql);
        $sentencia->bind_param("sssdiiissss", $codebar, $title, $descrip, $price, $cat, $cond, $disp, $link, $linkimage, $linkimageadd, $brand);
        $sentencia->execute();
        $sentencia->close();
    }

    /**
     * Edita el producto con codigo $codebar_edit y actualiza sus campos.
     */
    function edit_product($codebar_edit, $producto){
        //Recupero los valores del producto
        $codebar = $producto->get_codebar();
        $title = $producto->get_title();
        $descrip = $producto->get_description();
        $price = $producto->get_price();
        $cat = $producto->get_id_categoria();
        $disp = $producto->get_id_available();
        $cond = $producto->get_id_condition();
        $link = $producto->get_link_page();
        $linkimage = $producto->get_link_image();
        $linkimageadd = $producto->get_link_additional_image();
        $brand = $producto->get_marca();

        $sql = "UPDATE productos SET p_codebar=(?), p_title=(?), p_descrip=(?), p_price=(?), p_cat=(?), 
        p_condition=(?), p_available=(?), p_link=(?), p_linkimage=(?), p_linkimageextra=(?), p_brand=(?) 
        WHERE p_codebar=(?);";
        
        $sentencia = $this->mysql->prepare($sql);
        $sentencia->bind_param("sssdiiisssss", $codebar, $title, $descrip, $price, $cat, $cond, $disp, $link, 
            $linkimage, $linkimageadd, $brand, $codebar_edit);
        
        $sentencia->execute();
        $sentencia->close();
    }

    /**
     * Edita el producto con codigo $codebar_edit y actualiza sus campos.
     */
    function remove_product($codebar){
        $sql = "DELETE FROM productos WHERE p_codebar=(?);";
        
        $sentencia = $this->mysql->prepare($sql);
        $sentencia->bind_param("s", $codebar);
        
        $sentencia->execute();
        $sentencia->close();
    }

    /**
     * Recupera el producto correspondiente al codebar.
     */
    function search($codebar){
        $sql = "SELECT * FROM productos WHERE";
        //Sentencia
        $this->sentencia = $this->mysql->prepare($sql." p_codebar=(?);");
        $this->sentencia->bind_param("s", $codebar);
        //Ejecutar sql
        $this->sentencia->execute();
        //Crear objeto producto.
        $producto = new Producto();

        if ($this->sentencia->bind_result($_codebar, $_title, $_descrip, $_price, $_id_cat, $_cond, $_disp, $_link, $_linkimage, $_linkimageadd, $_brand)){
            //Poner variables por cada elemento a recuperar de la consulta
            $this->sentencia->fetch();
            
            //Cargo el objeto producto con los valores del producto encontrado.
            $producto->set_codebar($_codebar);
            $producto->set_title($_title);
            $producto->set_description($_descrip);
            $producto->set_price($_price);
            $producto->set_id_categoria($_id_cat);
            $producto->set_id_available($_disp);
            $producto->set_id_condition($_cond);
            //$producto->set_link_page($_link);
            $producto->set_link_image($_linkimage);
            $producto->set_link_additional_image($_linkimageadd);
            $producto->set_marca($_brand);
        }

        //Cierra la conexion a la db
        $this->sentencia->close();

        return $producto;
    }

    /*
    * Devuelve un arreglo de productos correspondiente a una categoria y subcategoria.
    */
    function search_all_for_categories($id_cat){
        $sql = "SELECT p_codebar, p_title, p_descrip, p_price, di_name, co_name, p_link, p_linkimage, p_linkimageextra, p_brand FROM productos, disponibilidad, condiciones WHERE p_condition=co_id and p_available=di_id";
        
        //CASO 1: Todas las categorias.
        if ($id_cat==0){
            $this->sentencia = $this->mysql->prepare($sql." ORDER BY p_title;");
        }
        else{
            //CASO 2: Categoria puntual
            $this->sentencia = $this->mysql->prepare($sql." and p_cat=(?) ORDER BY p_title;");
            $this->sentencia->bind_param("i", $id_cat);
        }
        //Ejecutar sql
        $this->sentencia->execute();
        //Inicializa el arreglo.
        $array_product = array();
        $pos = 0;
        

        //Recupera los resultados y los almacena en sus respectivas variables.
        if ($this->sentencia->bind_result($_codebar, $_title, $_descrip, $_price, $_diname, $_coname, $_link, $_linkimage, $_linkimageadd, $_brand)){
            //Poner variables por cada elemento a recuperar de la consulta
            while ($this->sentencia->fetch()){
                
                $producto = null;
                //Cargo el objeto producto con los valores del producto encontrado.
                $producto = new Producto();
                $producto->set_codebar($_codebar);
                $producto->set_title($_title);
                $producto->set_description($_descrip);
                $producto->set_price($_price);
                $producto->set_text_available($_diname);
                $producto->set_text_condition($_coname);
                //$producto->set_link_page($_link);
                $producto->set_link_image($_linkimage);
                $producto->set_link_additional_image($_linkimageadd);
                $producto->set_marca($_brand);
                //Almacena los productos.
                $array_product[$pos] = $producto;
                $pos = $pos + 1;
            }
        }

        //Cierra la conexion a la db
        $this->sentencia->close();

        return $array_product;
    }

    /*
    * Devuelve un arreglo de productos correspondiente a una categoria y subcategoria.
    */
    function search_all_custom(){
        //Sentencia para recuperar una lista de productos que cumplan con determinados filtros.
        $sql = "SELECT DISTINCT p_codebar, p_title, p_descrip, p_price, di_name, cat_google, co_name, p_link,
            p_linkimage, p_linkimageextra, p_brand 
            FROM productos, disponibilidad, condiciones, categorias 
            WHERE p_condition=co_id and p_available=di_id and p_cat=cat_id ";

        //Recupero los valores del arreglo POST y si tienen valor, las almaceno en sus respectivas variables
        $text = isset($_POST['txt_text']) ? $_POST['txt_text'] : "";
        $cat = isset($_POST['select_categoria']) ? (int) $_POST['select_categoria'] : "";
        $marca = isset($_POST['select_marca']) ? $_POST['select_marca'] : "";
        $precio_min = isset($_POST['sp_price_min']) ? (float) $_POST['sp_price_min'] : "";
        $precio_max = isset($_POST['sp_price_max']) ? (float) $_POST['sp_price_max'] : "";

        print_r($marca);

        //Filtro SQL
        $filtro_cat = ($cat!="" && $cat!=0) ? " AND cat_id=".$cat." " : "";
        $filtro_marca = ($marca!="") ? " AND p_brand='".$marca."' " : "";
        $filtro_precio = ($precio_min!="" && $precio_max!="") ? " AND (p_price BETWEEN ".$precio_min." AND ".$precio_max.") " : "";
        $filtro_title = ($text!="") ? " AND (p_title LIKE '%".$text."%' OR p_descrip LIKE '%".$text."%') " : "";

        //SQL
        $sql = $sql.$filtro_cat.$filtro_marca.$filtro_precio.$filtro_title;
        $this->sentencia = $this->mysql->prepare($sql." ORDER BY p_title");

        //Ejecutar sql
        $this->sentencia->execute();
        //Inicializa el arreglo.
        $array_product = array();
        $pos = 0;
        
        //Recupera los resultados y los almacena en sus respectivas variables.
        if ($this->sentencia->bind_result($_codebar, $_title, $_descrip, $_price, $_diname, $_coname, $_cat_google, $_link, $_linkimage, $_linkimageadd, $_brand)){
            //Poner variables por cada elemento a recuperar de la consulta
            while ($this->sentencia->fetch()){
                
                $producto = null;
                //Cargo el objeto producto con los valores del producto encontrado.
                $producto = new Producto();
                $producto->set_codebar($_codebar);
                $producto->set_title($_title);
                $producto->set_description($_descrip);
                $producto->set_price($_price);
                $producto->set_text_available($_diname);
                $producto->set_text_condition($_coname);
                $producto->set_categoria($_cat_google);
                //$producto->set_link_page($_link);
                $producto->set_link_image($_linkimage);
                $producto->set_link_additional_image($_linkimageadd);
                $producto->set_marca($_brand);
                //Almacena los productos.
                $array_product[$pos] = $producto;
                $pos = $pos + 1;
            }
        }

        //Cierra la conexion a la db
        $this->sentencia->close();

        return $array_product;
    }

    /*
    * Devuelve un arreglo de productos correspondiente a una categoria y subcategoria.
    * Este arreglo está pensado para exportar a un archivo CSV.
    */
    function search_all_for_categories_export(){
        $sql = "SELECT p_codebar, p_title, p_descrip, p_cat, di_name, co_name, p_price, p_link, p_linkimage, p_linkimageextra, p_brand 
        FROM productos, disponibilidad, condiciones 
        WHERE p_condition=co_id and p_available=di_id";

        $this->sentencia = $this->mysql->prepare($sql);
        
        //Ejecutar sql
        $this->sentencia->execute();
        //Inicializa el arreglo.
        $array_product = array();
        $pos = 0;

        //Recupera los resultados y los almacena en sus respectivas variables.
        if ($this->sentencia->bind_result($_codebar, $_title, $_descrip, $_cat_google, $_diname, $_coname, $_price, $_link, $_linkimage, $_linkimageadd, $_brand)){
            //Poner variables por cada elemento a recuperar de la consulta
            while ($this->sentencia->fetch()){
                //Cargo el objeto producto con los valores del producto encontrado.
                $producto = new Producto();
                $producto->set_codebar($_codebar);
                $producto->set_title($_title);
                $producto->set_description($_descrip);
                $producto->set_price($_price);
                $producto->set_text_available($_diname);
                $producto->set_text_condition($_coname);
                //$producto->set_link_page($_link);
                $producto->set_link_image($_linkimage);
                $producto->set_link_additional_image($_linkimageadd);
                $producto->set_marca($_brand);
                $producto->set_cat_product_google($_cat_google);
                
                //Almacena los productos.
                $array_product[$pos] = $producto;
                $pos = $pos + 1;
            }
        }

        //Cierra la conexion a la db
        $this->sentencia->close();

        return $array_product;
    }

    /**
     * Recupera un arreglo de arreglos con productos que tienen la imagen en construccion (no definida).
     * Cada subarray está compuesto de dos elementos: [0] codigo de barra, [1] Título.
     */
    function search_product_image_not_defined(){
        $sql = "SELECT p_codebar, p_title 
        FROM productos
        WHERE p_linkimage='https://drive.google.com/uc?export=view&id=1Mh8yFhGtvhq7AkKzs09jad8d5pjwADKi'";

        $this->sentencia = $this->mysql->prepare($sql);
        
        //Ejecutar sql
        $this->sentencia->execute();
        //Inicializa el arreglo.
        $array_product = array();
        $pos = 0;

        //Recupera los resultados y los almacena en sus respectivas variables.
        if ($this->sentencia->bind_result($_codebar, $_title)){
            //Poner variables por cada elemento a recuperar de la consulta
            while ($this->sentencia->fetch()){
                //Crea un arreglo de datos para el producto.
                $date_product = array(
                    0 => $_codebar,
                    1 => $_title
                );
                
                //Almacena los productos.
                $array_product[$pos] = $date_product;
                $pos = $pos + 1;
            }
        }

        //Cierra la conexion a la db
        $this->sentencia->close();

        return $array_product;
    }

}
?>