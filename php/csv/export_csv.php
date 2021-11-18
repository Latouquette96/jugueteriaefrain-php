<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/csv/export_csv_product-class.php";
    
    session_start();
    $_SESSION['obj_csv_export'] = (isset($_SESSION['obj_csv_export'])) ? $_SESSION['obj_csv_export'] :new ExportCSVProductClass();

    $obj_html = $_SESSION['obj_csv_export'];
    
    if (isset($_POST['btn_submit'])){
        $obj_html->construir_html("Exportar productos a .CSV");
        $obj_html->exportar();
        $obj_html->set_resultado_exportar();
    }
    else{
        $obj_html->construir_html("Exportar productos a .CSV");
    }
?>