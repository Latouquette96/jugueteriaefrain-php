<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/conexion_mysql.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";

class CSVMySQL {
    //Atributos de instancia

    /**
    * Constructor de configuracion
    */
    function __construct(){}

    /*
    * Importar registros del archivo hacia la base de datos.
    */
    function importar_registros($name_archi, $id_cat_destino){
        //Crea un objeto con la conexion mysql del producto.
        $prod_mysql = new ProductoMySQL("Latouquette96", "39925523");
        //Crea un objeto configuracion
        $config = new ConfiguracionMySQL("Latouquette96", "39925523");
        $directory = $config->get_directory_catalog();
        $pathfile = $directory."/".$name_archi;
        //Abrir archivo
        setlocale(LC_ALL, 'es_AR.UTF8');
        $file = fopen($pathfile,"r");
        $data = fgetcsv ($file, 1000, ",");

        setlocale(LC_ALL, 'es_AR.UTF8');
        while ($data = fgetcsv($file, 1000, ",")) {
            $num = count($data);
            $prod = new Producto();
            $prod->cargar_array_csv($data, $id_cat_destino);

            $prod_mysql->new_product($prod);
        }

        fclose($file);
    }

    /*
    * Exportar registros de la base de datos hacia su respectivo archivo.
    */
    function exportar_registros($name_file, $array_product_export){
        //Crea un objeto con la conexion mysql del producto.
        $config = new ConfiguracionMySQL("Latouquette96", "39925523");

        //Recupera el directorio destino
        $directory_destino = $config->get_directory_catalog();
        $pathfile = $directory_destino."/".$name_file.".csv";
        
        //Abre/crea/sobreescribe el archivo
        $file = fopen($pathfile, "w");
        setlocale(LC_ALL, 'es_AR.UTF8');
        
        //Insertar header
        $header = "id, title, description, google_product_category, fb_product_category, availability, condition, price, link, image_link, additional_image_link, brand\n";
        fwrite($file, $header.PHP_EOL);

        foreach ($array_product_export as $producto){
            $str = $producto->get_to_string_export();
            fwrite($file, $str.PHP_EOL);
        }

        //Cierra el archivo.
        fclose($file);
    }
}
?>