<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/remove_product-class.php";
    
    session_start();
    $_SESSION['obj_remove_product'] = (isset($_SESSION['obj_remove_product'])) ? $_SESSION['obj_remove_product'] :new RemoveProductClass();

    $obj_html = $_SESSION['obj_remove_product'];
    
    if (isset($_POST['btn-next'])){
        $obj_html->construir();
        $obj_html->remover_producto();
        $obj_html->set_resultado();
    }
    else{
        $obj_html->construir();
    }
?>