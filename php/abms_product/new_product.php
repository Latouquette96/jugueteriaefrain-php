<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/new_product-class.php";
    
    $_SESSION['obj_new_product'] = (isset($_SESSION['obj_new_product'])) ? $_SESSION['obj_new_product'] :new NewProductClass();

    $obj_html = $_SESSION['obj_new_product'];
    
    if (isset($_POST['btn-next'])){
        $obj_html->construir();
        $obj_html->insertar_producto();
        $obj_html->set_resultado();
    }
    else{
        $obj_html->construir();
    }
?>