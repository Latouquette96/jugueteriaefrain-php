<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/index-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/config_mysql.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";

    $obj_html = new IndexClass();
    $obj_html->construir_html();
?>