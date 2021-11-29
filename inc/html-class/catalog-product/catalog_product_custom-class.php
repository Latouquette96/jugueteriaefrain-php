<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/marca_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";

class CatalogProductCustomClass extends PlantillaHTMLPHP{

    function CatalogProductCustomClass(){
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
        
        echo "<body onload=inicializar_controles()>";

        $this->_construir_nav();

        echo "<div class='div-cuerpo'>";
        $this->_construir_encabezado($title);
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
            echo "<legend class='col-form-label'>Filtros de búsqueda</legend>";
            $this->construir_formulario_search();
        echo "</div>";
        

        //Si se ha seleccionado una categoria, entonces se muestra el catalogo de productos.
        if (isset($_POST['btn_search'])){
            echo "<div id='div-catalogo' class='container'>";
                echo "<legend class='col-form-label'>Listado de productos</legend>";
                $this->construir_catalogo_productos();
            echo "</div>";
        }
    }

    /**
     * Construye el formulario de selección de categoria.
     */
    protected function construir_formulario_search(){
        echo "<form action='' method='POST' name='form-catalogo' class='form-constructor'>";
            $this->_set_search_select_categorias();
            $this->_set_search_select_marcas();
            $this->_set_search_texto();
            $this->_set_search_rango_precios();
            $this->_set_button_search();
        echo "</form>";
    }

    protected function _set_search_select_categorias(){
        echo "<div class='form-group'>";

            //Comprueba si la casilla de verificacion
            $checked = isset($_POST['check_select_cat']) ? " checked " : "";
            
            echo "<div class='form-check'>";
                echo "<input class='form-check-input' type='checkbox' value='select-categoria' id='check_select_cat' name='check_select_cat' ".$checked." onclick=update_select_categoria()>";
                echo "<label class='form-check-label' for='check_select_cat'>Búsqueda por categoria</label>";
            echo "</div>";

            echo "<div class='form-group'>";
                $obj_cat = new CategoriaMySQL("Latouquette96","39925523");

                echo "<input class='form-control' list='list_categoria' id='select_categoria' name='select_categoria' placeholder='Category to search...'>";
                echo "<datalist id='list_categoria' name='list_categoria'>";

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
                echo "</datalist>";
            echo "</div>";

        echo "</div>";
    }

    protected function _set_search_select_marcas(){
        echo "<div class='form-group'>";

            //Casilla de verificacion
            echo "<div class='form-check'>";

                //Comprueba si la casilla de verificacion
                $checked = isset($_POST['check_select_marca']) ? " checked " : "";

                echo "<input class='form-check-input' type='checkbox' value='select-marca' id='check_select_marca' name='check_select_marca' ".$checked." onclick=update_select_marca()>";
                echo "<label class='form-check-label' for='check_select_marca'>Búsqueda por marca</label>";
            echo "</div>";

            echo "<div class='form-group'>";
                $obj_marca = new MarcaMySQL("Latouquette96","39925523");

                echo "<input class='form-control' list='list_marca' id='select_marca' name='select_marca' placeholder='Brand to search...'>";
                echo "<datalist id='list_marca' name='list_marca'>";

                //Arreglo de marcas
                $array_marcas = $obj_marca->get_array_marca();
                
                //Obtiene el producto seleccionado
                $id_marca = (isset($_POST['select_marca'])) ? (int) array_search($_POST['select_marca'], $array_marcas) : 0;
                $marca_select = $array_marcas[$id_marca];
                        
                //Establece como seleccionada la marca encontrada.
                //Si el valor es 0, entonces se la marca como desabilitada y seleccionada
                echo "<option value=".($marca_select)." selected>".$marca_select."</option>";

                //Remueve la marca seleccionada del arreglo
                unset($array_marcas[$id_marca]);

                //Recorre el arreglo de marcas y lo inserta como opcion
                foreach($array_marcas as $dato){
                    $marca = $dato;
                    echo "<option value='".$marca."'>".$marca."</option>";                           
                }
                echo "</datalist>";
            echo "</div>";
        
        echo "</div>";
    }

    protected function _set_search_texto(){
        echo "<div class='form-group'>";
            //Casilla de verificacion
            echo "<div class='form-check'>";

                //Comprueba si la casilla de verificacion
                $checked = isset($_POST['check_texto']) ? " checked " : "";

                echo "<input class='form-check-input' type='checkbox' value='select-text' id='check_texto' name='check_texto' ".$checked." onclick=update_texto()></input>";
                echo "<label class='form-check-label' for='check_texto'>Búsqueda por texto</label>";
            echo "</div>";
        
            echo "<div class='form-group'>";
                //Recupera los valores de ambos input en caso de que existan.
                $texto = isset($_POST['txt_text']) ? $_POST['txt_text'] : "";

                echo "<textarea type='text' id='txt_text' name='txt_text' class='form-control' rows='2'>".$texto."</textarea>";
            echo "</div>";

        echo "</div>";
    }

    protected function _set_search_rango_precios(){
        echo "<div class='form-group'>";
            //Casilla de verificacion
            echo "<div class='form-check'>";

                //Comprueba si la casilla de verificacion
                $checked = isset($_POST['check_price']) ? " checked " : "";

                echo "<input class='form-check-input' type='checkbox' value='select-precio' id='check_price' name='check_price' ".$checked." onclick=update_range_price()>";
                echo "<label class='form-check-label' for='check_price'>Búsqueda por rango de precios</label>";
            echo "</div>";
        
            echo "<div class='form-group row'>";

                //Recupera los valores de ambos input en caso de que existan.
                $valor_min = isset($_POST['sp_price_min']) ? (float) $_POST['sp_price_min'] : 0;
                $valor_max = isset($_POST['sp_price_max']) ? (float) $_POST['sp_price_max'] : 0;

                echo "<div class='col'>";
                    echo "<input type='number' id='sp_price_min' name='sp_price_min' class='form-control' value=".$valor_min."></input>";
                echo "</div>";
                echo "<div class='col'>";
                    echo "<input type='number' id='sp_price_max' name='sp_price_max' class='form-control' value=".$valor_max."></input>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    }

    protected function _set_button_search(){
        echo "<div class='form-group row'>";
        echo "<input name='btn_search' id='btn_search' class='btn btn-primary' type='submit' value='Cargar...'></input>";
        echo "</div>";
    }

    /**
     * Construye el catalogo de productos.
     */
    function construir_catalogo_productos(){
        //Recupera el registro de productos de dicha categoria.
        $producto_mysql = new ProductoMySQL("Latouquette96", "39925523");
        $array_product = $producto_mysql->search_all_custom();
        
        //Total de productos
        $total_product = count($array_product);

        echo "<div class='container'>";
            //Imprime la cantidad de productos devueltos.
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
        echo "</div'>";
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

    /**
     * Muestra un cuadro con el resultado de la operación.
     */
    protected function _set_resultado($total_reg){
        echo "<div class='alert alert-success' role='alert'>";
            echo "Se recuperaron <b>".$total_reg." productos</b> que cumplen con las condiciones del filtro.";
        echo "</div>";
    }
}
?>

<script type = "text/javascript">

    function inicializar_controles(){
        update_select_categoria();
        update_select_marca();
        update_texto();
        update_range_price();
    }

    function update_select_categoria(){
        //Variables de control checkbox
        var control_cat = document.getElementById("check_select_cat");
        var select_cat = document.getElementById("select_categoria");
        //Habilita/deshabilita el select categoria.
        select_cat.disabled = !control_cat.checked;
    }
    
    function update_select_marca(){
        //Variables de control checkbox
        var control_marca = document.getElementById("check_select_marca");
        var select_marca = document.getElementById("select_marca");
        //Habilita/deshabilita el select marca.
        select_marca.disabled = !control_marca.checked;
    }

    function update_texto(){
        //Variables de control checkbox
        var control_text = document.getElementById("check_texto");
        var texto = document.getElementById("txt_text");
        //Habilita/deshabilita el texto.
        texto.disabled = !control_text.checked;
    }

    function update_range_price(){
        //Variables de control checkbox
        var control_price = document.getElementById("check_price");
        var price_min = document.getElementById("sp_price_min");
        var price_max = document.getElementById("sp_price_max");
        //Habilita/deshabilita el rango de precios.
        price_min.disabled = !control_price.checked;
        price_max.disabled = !control_price.checked;

    }

</script>