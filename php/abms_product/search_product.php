<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/search_product-class.php";
    
    session_start();
    $_SESSION['obj_search_product'] = (isset($_SESSION['obj_search_product'])) ? $_SESSION['obj_search_product'] :new SearchProductClass();

    $obj_html = $_SESSION['obj_search_product'];
    
    if (!isset($_POST['btn-next'])){
        $obj_html->construir();
    }
    else{
        $obj_html->construir();
    }
?>