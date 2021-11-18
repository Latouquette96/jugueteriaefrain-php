<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/edit_product-class.php";
    
    session_start();
    $_SESSION['obj_edit_product'] = (isset($_SESSION['obj_edit_product'])) ? $_SESSION['obj_edit_product'] :new EditProductClass();

    $obj_html = $_SESSION['obj_edit_product'];
    
    if (isset($_POST['btn-next'])){
        $obj_html->construir();
        $obj_html->editar_producto();
        $obj_html->set_resultado();
    }
    else{
        $obj_html->construir();
    }
?>