<?php

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
        $this->_construir_inicio_body(null);
        $this->_construir_nav();
        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }
}

?>