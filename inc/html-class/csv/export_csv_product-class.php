<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/config_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/csv_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";

class ExportCSVProductClass extends PlantillaHTMLPHP{
    //Atributos de instancia

    function ExportCSVProductClass(){
        parent:: __construct();        
    }

    /**
     * Construye el html.
     */
    function construir_html($title){
        $this->_construir_inicio_header();
        $this->_construir_title($title);
        $this->_construir_bloques_css(true, true);
        $this->_construir_fin_header();
        $this->_construir_inicio_body();
        $this->_construir_nav();

        echo "<div class='div-cuerpo'>";
        $this->_construir_encabezado($title);
        $this->_construir_formulario();

        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }

    /**
     * Construye el formulario.
     */
    protected function _construir_formulario(){
        echo "<div class='container' id='div-import-export'>";
            echo "<legend>Formulario Exportar productos</legend>";
            echo "<form method='POST' action=''>";
                $this->set_select_categoria();
                $this->set_button_submit();
            echo "</form>";
        echo "</div>";
    }

    /**
     * Establece el campo del select categoria.
     */
    protected function set_select_categoria(){
        echo "<div class='form-group'>";
            $obj_cat = new CategoriaMySQL("Latouquette96","39925523");

            echo "<label for='select-cat'>Seleccion de categoria:</label>";
            echo "<input class='form-control' list='list_categoria' id='select-cat' name='select-cat' placeholder='Category to search...'>";
            echo "<datalist id='list_categoria' name='list_categoria'>";

            $array_cat = $obj_cat->get_array_categorias_subcategorias();

            //Recorre el arreglo de categorias y lo inserta como opcion
            foreach($array_cat as $dato){
                echo "<option value=".$dato[0].">".$dato[1]."</option>";                      
            }              
            echo "</datalist>"; 
        echo "</div>";
    }

    /**
     * Establece el boton submit del formulario.
     */
    protected function set_button_submit(){
        echo "<div class='form-group row'>";
        echo "<input id='btn_submit' name='btn_submit' class='btn btn-primary' type='submit' value='Confirmar'></input>";
        echo "</div>";
    }

    
    /**
     * Devuelve un arreglo con los catalogos del directorio.
     */
    protected function _get_files_directory_catalogs(){
        $array_csv = array();
        // Ruta del directorio donde están los archivos
        $conf = new ConfiguracionMySQL("Latouquette96", "39925523");
        $path = $conf->get_directory_catalog();

        // Arreglo con todos los nombres de los archivos
        $coleccion_files = scandir($path."/OLD");

        foreach($coleccion_files as $file){
            if (strstr($file,'.csv')){
                array_push($array_csv, $file);
            }
        }

        return $array_csv;
    }

    /**
     * Exporta los productos de una o mas categorias a archivos csv.
     */
    function exportar(){
        //Objetos de mysql
        $csv_mysql = new CSVMySQL("Latouquette96", "39925523");
        $producto_mysql = new ProductoMySQL("Latouquette96", "39925523");
        $obj_cat = new CategoriaMySQL("Latouquette96", "39925523");

        //Recupera el identificador de la categoria.
        $id_cat= (int) $_POST['select-cat'];
        
        //Si el id es distinto de 0, entonces se exporta un determinado 
        if ($id_cat!=0){
            //Recupera el nomnbre del archivo (sin extension)
            $name_file = $obj_cat->get_name_category_subcategory($id_cat);
            //Recuperar productos
            $array_producto = $producto_mysql->search_all_for_categories_export($id_cat);
            //Exportar registros
            $csv_mysql->exportar_registros($name_file, $array_producto);
        }
        else{
            //Para cada identificador de categoria.
            foreach($obj_cat->get_all_identificador_category() as $id){
                //Recupera el nomnbre del archivo (sin extension)
                $name_file = $obj_cat->get_name_category_subcategory($id);
                //Recuperar productos
                $array_producto = $producto_mysql->search_all_for_categories_export($id);
                //Exportar registros
                $csv_mysql->exportar_registros($name_file, $array_producto);
            }
        }
    }

    /**
     * Muestra el resultado de la operación.
     */
    function set_resultado_exportar(){
        echo "<div id='div-resultado' class='alert alert-success' role='alert'>";
        echo "¡Productos exportados a archivo CSV con exito!";
        echo "</div>";
    }
}
?>