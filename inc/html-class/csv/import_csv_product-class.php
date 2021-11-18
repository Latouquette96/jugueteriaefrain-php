<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/config_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/csv_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";

class ImportCSVProductClass extends PlantillaHTMLPHP{
    //Atributos de instancia

    function ImportCSVProductClass(){
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
        if ($title!=null && $title!=""){
            echo "<h2 id='titulo' name='titulo'>$title</h2>";
        }
        $this->_construir_formulario();

        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }

    /**
     * Construye el formulario.
     */
    protected function _construir_formulario(){
        echo "<div class='container' id='div-import-export'>";
            echo "<legend>Formulario importar productos</legend>";
            echo "<form method='POST' action=''>";
                $this->set_select_archivos_catalogos();
                $this->set_select_categoria();
                $this->set_button_submit();
            echo "</form>";
        echo "</div>";
    }

    /**
     * Crea el campo de seleccion con los catalogos almacenados en el servidor.
     */
    protected function set_select_archivos_catalogos(){
        echo "<div class='form-group'>";
            echo "<label for='select-catalog'>Seleccion de catalogo (CSV):</label>";
            echo "<select name='select-catalog' class='form-control'></br>";

            //Recorre el arreglo de categorias y lo inserta como opcion
            foreach($this->_get_files_directory_catalogs() as $dato){
                //Si la clave coincide, entonces ...
                echo "<option>".$dato."</option>";                           
            }              
            echo "</select>"; 
        echo "</div>";
    }

    /**
     * Establece el campo del select categoria.
     */
    protected function set_select_categoria(){
        echo "<div class='form-group'>";
            $obj_cat = new CategoriaMySQL("Latouquette96","39925523");

            echo "<label for='select-cat'>Seleccion de categoria:</label>";
            echo "<select name='select-cat' class='form-control'>";

            $array_cat = $obj_cat->get_array_categorias_subcategorias();
            unset($array_cat[0]);

            //Recorre el arreglo de categorias y lo inserta como opcion
            foreach($array_cat as $dato){
                echo "<option value=".$dato[0].">".$dato[1]."</option>";                      
            }              
            echo "</select>"; 
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
     * Establece el boton submit del formulario.
     */
    protected function set_button_submit(){
        echo "<div class='form-group row'>";
        echo "<input id='btn_submit' name='btn_submit' class='btn btn-primary' type='submit' value='Confirmar'></input>";
        echo "</div>";
    }

    function importar(){
        $name_archi = $_POST['select-catalog'];
        $id_cat_destino = (int) $_POST['select-cat'];
        
        $csv_mysql = new CSVMySQL("Latouquette96", "39925523");
        $csv_mysql->importar_registros($name_archi, $id_cat_destino);
    }

    function set_resultado_importar(){
        echo "<div class='alert alert-success' role='alert'>";
        echo "¡Productos importados con exito!";
        echo "</div>";
    }
}
?>