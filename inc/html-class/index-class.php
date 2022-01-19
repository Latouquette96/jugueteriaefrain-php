<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";

class IndexClass extends PlantillaHTMLPHP{

    function IndexClass(){
        parent:: __construct();
    }

    /**
     * Construye el html.
     */
    function construir_html(){
        $this->_construir_inicio_header();
        $this->_construir_title("Inicio");
        $this->_construir_bloques_css();
        $this->_construir_fin_header();
        $this->_construir_encabezado("PANEL DE INICIO");
        $this->_construir_inicio_body(null);
        $this->_construir_nav();
        $this->_set_panel_novedades();
        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }

    /**
     * Establece el panel de novedades de los productos.
     */
    protected function _set_panel_novedades(){
        echo "<div id='panel_novedades' class='container'>";
            $this->_set_listado_productos_img_construccion();
        echo "</div>";
    }

    /**
     * Muestra el listado de productos con imagen en construcción.
     */
    protected function _set_listado_productos_img_construccion(){
        echo "<h3 class=h3-title-top id='titulo_h3' name='titulo_h3'>Listado de productos sin imagen definida.</h3>";
        echo "<div id='list_prod_img' class='container'>";
            $prod_mysql = new ProductoMySQL("Latouquette96", "39925523");
            $array_product = $prod_mysql->search_product_image_not_defined();
                
            echo "<ul class='list-group'></ul>";
            $i=0;

            foreach ($array_product as $data_product){
                if (($i % 2) == 0){
                    echo "<a href=\"/jugueteriaefrain/php/abms_product/edit_product.php?codebar_product=".$data_product[0]."\"
                     class='list-group-item list-group-item-action list-group-item-primary'>".$data_product[0]." - ".$data_product[1]."</a>";
                }
                else{
                    echo "<a href=\"/jugueteriaefrain/php/abms_product/edit_product.php?codebar_product=".$data_product[0]."\"
                     class='list-group-item list-group-item-action list-group-item-info'>".$data_product[0]." - ".$data_product[1]."</a>";
                }
                $i = $i + 1;
            }

            echo "</ul>";
        echo "</div>";
    }
}

?>