<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/csv/import_csv_product-class.php";
    
    session_start();
    $_SESSION['obj_csv_import'] = (isset($_SESSION['obj_csv_import'])) ? $_SESSION['obj_csv_import'] :new ImportCSVProductClass();

    $obj_html = $_SESSION['obj_csv_import'];
    
    if (isset($_POST['btn_submit'])){
        $obj_html->construir_html("Importar productos de .CSV");
        $obj_html->importar();
        $obj_html->set_resultado_importar();
    }
    else{
        $obj_html->construir_html("Importar productos de .CSV");
    }
?>