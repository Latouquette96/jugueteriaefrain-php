<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";

class CatalogProductClass extends PlantillaHTMLPHP{

    function CatalogProductClass(){
        parent:: __construct();
    }

    /**
     * Construye el html.
     */
    function construir_html($title){
        $this->_construir_inicio_header();
        $this->_construir_title($title);
        $this->_construir_bloques_css();
        $this->_construir_fin_header();
        $this->_construir_inicio_body();
        $this->_construir_nav();

        echo "<div class='div-cuerpo'>";
        if ($title!=null && $title!=""){
            echo "<h2 id='titulo' name='titulo'>$title</h2>";
        }
        $this->_constructor_formulario();

        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }

    /**
     * Construye el formulario.
     */
    protected function _constructor_formulario(){
        //Construye el formulario categoria.
        echo "<div class='container' id='div-search'>";
            echo "<legend class='col-form-label'>Seleccion de catalogo</legend>";
            $this->construir_formulario_categoria();
        echo "</div>";
        
        //Si se ha seleccionado una categoria, entonces se muestra el catalogo de productos.
        if (isset($_POST['select_categoria'])){
            echo "<div id='div-catalogo' class='container'>";
                echo "<legend class='col-form-label'>Listado de productos</legend>";
                $this->construir_catalogo_productos();
            echo "</div>";
        }
    }

    /**
     * Construye el formulario de selección de categoria.
     */
    protected function construir_formulario_categoria(){
        //Objeto categoria_mysql
        $obj_cat = new CategoriaMySQL("Latouquette96","39925523");
        
        echo "<form action='' method='POST' name='form-catalogo' class='form-constructor'>";
       
        echo "<div class='form-group'>";
            echo "<select id='select_categoria' class='form-control' name='select_categoria'>";

            //Arreglo de categorias
            $array_categorias = $obj_cat->get_array_categorias_subcategorias();
            $array_categorias[0][1] = "Todos los productos";
            
            //Obtiene el producto seleccionado
            $id_cat = (isset($_POST['select_categoria'])) ? (int) $_POST['select_categoria'] : 0;
            $cat_select = $array_categorias[$id_cat];
                    
            //Establece como seleccionada la categoria encontrada.
            //Si el valor es 0, entonces se la marca como desabilitada y seleccionada
            echo "<option value=".((int) $cat_select[0])." selected>"
                .$cat_select[1]."</option>";

            //Remueve la categoria seleccionada del arreglo
            unset($array_categorias[$id_cat]);


            //Recorre el arreglo de categorias y lo inserta como opcion
            foreach($array_categorias as $dato){
                $categ = $dato;
                echo "<option value=".((int) $categ[0]).">".$categ[1]."</option>";                           
            }
            echo "</select>";
        echo "</div>";

        echo "<div class='form-group row'>";
        echo "<input name='btn_search' id='btn_search' class='btn btn-primary' type='submit' value='Cargar...'></input>";
        echo "</div>";

        echo "</form>";
    }

    /**
     * Construye el catalogo de productos.
     */
    function construir_catalogo_productos(){
        //Recupera y castea el identificador de la categoria seleccionada.
        $id_cat = (int) $_POST['select_categoria'];
        //Recupera el registro de productos de dicha categoria.
        $producto_mysql = new ProductoMySQL("Latouquette96", "39925523");
        $array_product = $producto_mysql->search_all_for_categories($id_cat);
        //Total de productos
        $total_product = count($array_product);

        echo "<div>";
            $this->_set_resultado($total_product);
        
    
        /*Cada grupo de productos contendrá como mucho 3 productos.
         *Para agilizar calculos (a costa de reservar dos espacios nulos como mucho) 
         se calcula los espacios necesarios para que cada grupo contenga 3 productos.
        */
        $espacios_faltantes = 3 - ($total_product % 3);
        if ($espacios_faltantes > 0){
            for ($i=0; $i<$espacios_faltantes; $i++){
                array_push($array_product, null);
            }
            $total_product = $total_product + $espacios_faltantes;
        }

        //Variable de posicion
        $pos = 0;
        //Mientras existan elementos que leer...
        while($total_product>0){
            //crea una fila.
            echo "<div class='row'>";
                $total_product = $total_product - 3;
                //Construye el arreglo de 3 productos.
                $array = array();
                $array[0] = $array_product[$pos];
                $array[1] = $array_product[$pos+1];
                $array[2] = $array_product[$pos+2];
                
                $this->_set_group_card_product($array);

                $pos = $pos + 3;

            echo "</div>";
        }     

        echo "</div>";
    }

    /**
     * Muestra un cuadro con el resultado de la operación.
     */
    protected function _set_resultado($total_reg){
        echo "<div class='alert alert-success' role='alert'>";
            echo "Se recuperaron <b>".$total_reg." productos</b> que cumplen con las condiciones del filtro.";
        echo "</div>";
    }

    /**
     * Recibe un array de como mucho 3 elementos y carga sus respectivas tarjetas.
     */
    protected function _set_group_card_product($array_product){
        echo "<div class='card-group'>";
        $pos = 0;
        while($pos<3 && $array_product[$pos]!=null){
            //Recupera el producto y lo almacena en una variable.
            $producto = $array_product[$pos];
            //Crea la tarjeta para el producto.
            $this->_set_card_product($producto);
            $pos = $pos + 1;
        }
        echo "</div>";
    }

    /**
     * Crea la tarjeta del producto.
     */
    protected function _set_card_product($producto){
        echo "<div class='card border-primary mb-3' style='max-width: 360px;'>";
            echo "<img src='".$producto->get_link_image()."' class='card-img-top' alt='...'>";
            echo "<div class='card-body'>";
                echo "<h5 class='card-title'>".$producto->get_title()."</h5>";
                echo "<p class='card-text'>".$producto->get_description()."</p>";
            echo "</div>";
            echo "<ul class='list-group list-group-flush'>";
                echo "<li class='list-group-item'><b>Código: ".$producto->get_codebar()."</b></li>";
                echo "<li class='list-group-item'><b>Precio:</b> $ ".$producto->get_price()."</li>";
                echo "<li class='list-group-item'>".$producto->get_text_available()." - ".$producto->get_text_condition()."</li>";
            echo "</ul>";
        echo "</div>";
    }
}
?>