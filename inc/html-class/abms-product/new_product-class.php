<?php
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/abms-product/abms_product-class.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";

class NewProductClass extends ABMSProductClass{
    //Atributos de instancia

    function NewProductClass(){
        parent:: __construct();
        
    }

    function construir(){
        $this->construir_html("Nuevo producto", false, true);
    }

    /**
     * Inserta el producto en la base de datos.
     */
    function insertar_producto(){
        $producto = new Producto();

        $codebar = $_POST['sp_codebar'];
        $title = $_POST['txt_title'];
        $marca = $_POST['txt_marca'];
        $descrip = $_POST['txt_descript'];
        $price = (float) $_POST['sp_price'];
        $disp = (int) $_POST['select_disp'];
        $cond = (int) $_POST['select_cond'];
        //$link_page = $_POST['txt_link_page'];
        $link_image = $_POST['txt_link_image'];
        $link_image_add = $_POST['txt_link_image_extra'];
        $id_categoria = (int) $_POST['select_categoria'];

        $producto->set_codebar($codebar);
        $producto->set_title($title);
        $producto->set_description($descrip);
        $producto->set_price($price);
        $producto->set_id_categoria($id_categoria);
        $producto->set_id_available($disp);
        $producto->set_id_condition($cond);
        //$producto->set_link_page($link_page);
        $producto->set_link_image($link_image);
        $producto->set_marca($marca);
        $producto->set_link_additional_image($link_image_add);

        $abm_producto = new ProductoMySQL("Latouquette96", "39925523");
        $abm_producto->new_product($producto);
    }
}

?>