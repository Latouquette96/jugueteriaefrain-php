<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/catalog-product/catalog_product_custom-class.php";
    
    session_start();
    $_SESSION['obj_catalog_product_custom'] = (isset($_SESSION['obj_catalog_product_custom'])) ? $_SESSION['obj_catalog_product_custom'] :new CatalogProductCustomClass();

    $obj_html = $_SESSION['obj_catalog_product_custom'];
    
    $obj_html->construir_html("Catalogo de productos personalizado");
?>