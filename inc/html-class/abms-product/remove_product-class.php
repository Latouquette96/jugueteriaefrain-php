<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/abms_product-class.php";

class RemoveProductClass extends ABMSProductClass{
    //Atributos de instancia

    function RemoveProductClass(){
        parent:: __construct();
    }

    function construir(){
        $this->construir_html("Remover producto", true, false);
    }

    /**
     * Remueve el producto en la base de datos.
     */
    function remover_producto(){
        $codebar_search = $_POST['num_codebar_search'];

        $abm_producto = new ProductoMySQL("Latouquette96", "39925523");
        $abm_producto->remove_product($codebar_search);
    }
}

?>