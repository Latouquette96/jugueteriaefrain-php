<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/catalog-product/catalog_product-class.php";
    
    session_start();
    $_SESSION['obj_catalog_product'] = (isset($_SESSION['obj_catalog_product'])) ? $_SESSION['obj_catalog_product'] :new CatalogProductClass();

    $obj_html = $_SESSION['obj_catalog_product'];
    
    $obj_html->construir_html("Catalogo de productos");
?>