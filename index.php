<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/index-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/config_mysql.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";

    $obj_html = new IndexClass();
    $obj_html->construir_html();

    /*
    $config = new ConfiguracionMySQL("Latouquette96", "39925523");
    $cat = new CategoriaMySQL("Latouquette96", "39925523");

    $dir_destino = $config->get_directory_imagen()."/";

    foreach($cat->get_array_categorias_subcategorias() as $array_cat){
        $str = $dir_destino.str_replace(">","",$array_cat[1]);
        if (!is_dir($str)){
            mkdir($str, 0777);
        }
    }
    */
?>