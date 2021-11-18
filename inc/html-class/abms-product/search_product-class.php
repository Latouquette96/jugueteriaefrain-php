<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/abms_product-class.php";

class SearchProductClass extends ABMSProductClass{
    //Atributos de instancia

    function SearchProductClass(){
        parent:: __construct();
        
    }

    function construir(){
        $this->construir_html("Buscar producto", true, false);
    }
}

?>