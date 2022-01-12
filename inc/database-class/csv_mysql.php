<?php
    header('Content-Type: text/html; charset=UTF-8');
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
    function importar_registros($id_cat_destino){
        //Crea un objeto con la conexion mysql del producto.
        $prod_mysql = new ProductoMySQL("Latouquette96", "39925523");
        //Crea un objeto configuracion
        $config = new ConfiguracionMySQL("Latouquette96", "39925523");
        $directory = $config->get_directory_catalog();
        $pathfile = $directory."/catalogo_productos.csv";
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
    function exportar_registros($array_product_export){
        //Crea un objeto con la conexion mysql del producto.
        $config = new ConfiguracionMySQL("Latouquette96", "39925523");

        //Recupera el directorio destino
        $directory_destino = $config->get_directory_catalog();
        $pathfile = $directory_destino."/catalogo_productos.csv";
        
        //Delimitador
        $delimiter = ",";

        //Abre/crea/sobreescribe el archivo
        $file = fopen($pathfile, "w");
        setlocale(LC_ALL, 'es_AR.UTF8');

        //Insertar header
        //$header = "id, title, description, google_product_category, fb_product_category, availability, condition, price, link, image_link, additional_image_link, brand\n";
        $header = array('id', 'title', 'description', 'google_product_category', 'fb_product_category', 'availability', 'condition', 'price', 'link', 'image_link', 'additional_image_link', 'brand');

        //fwrite($file, $header.PHP_EOL);
        fputcsv($file, $header, $delimiter);

        foreach ($array_product_export as $producto){
            $line_product = $producto->get_to_string_export();
            fputcsv($file, $line_product, $delimiter);
        }

        //Cierra el archivo.
        fclose($file);
    }
}
?>