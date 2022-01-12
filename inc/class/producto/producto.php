<?php
class Producto {
    //Atributos de instancia
    protected $codebar;
    protected $title;
    protected $description;
    protected $price;
    protected $categoria;
    protected $id_categoria;
    protected $subcategoria;
    protected $condition;
    protected $id_condition;
    protected $available;
    protected $id_available;
    protected $disp;
    protected $link_page;
    protected $link_image;
    protected $link_additional_image;
    protected $array_link_additional = array();
    protected $marca;
    protected $cat_product_facebook;
    protected $cat_product_google;

    /*
    * Constructor de un producto.
    */
    public function __construct(){
        $this->codebar = "";
        $this->title = "";
        $this->description = "";
        $this->price = 0;
        $this->categoria = "";
        $this->id_categoria = 0;
        $this->subcategoria = "";
        $this->condition = "";
        $this->available = "";
        $this->disp = "";
        $this->link_page = 'https://www.facebook.com/jugueteriaefrain';
        $this->link_image = 'https://drive.google.com/uc?export=view&id=1Mh8yFhGtvhq7AkKzs09jad8d5pjwADKi';
        $this->link_additional_image = '';
        $this->marca = '';
        $this->id_condition = 0;
        $this->id_available = 0;
        $this->cat_product_facebook = "";
        $this->cat_product_google = "";
    }    

    /*
    * SETERS
    */

    function set_codebar($cb){
        $this->codebar = $cb;
    }

    function set_title($title){
        $this->title = $title;
    }

    function set_description($desc){
        $this->description = $desc;
    }

    function set_price($price){
        $this->price = $price;
    }

    function set_categoria($categ){
        $this->categoria = $categ;
    }

    function set_subcategoria($subcateg){
        $this->subcategoria = $subcateg;
    }

    function set_id_categoria($id_cat){
        $this->id_categoria = $id_cat;
    }

    function set_id_condition($cond_int){
        //Almacena el texto del string
        $this->id_condition = (int) $cond_int;
        //Variable
        $text = "";
        //Distintos casos de acuerdo a la variable.
        switch($cond_int){
            case 1:
                $text = '"new"';
                break;
            case 2:
                $text = '"used"';
                break;
        }
        //Almacena el resultado
        $this->condition = $text;
    }

    function set_id_available($disp_int){
       //Almacena el texto del string
       $this->id_available = (int) $disp_int;
       //Variable
       $text = "";
       //Distintos casos de acuerdo a la variable.
       switch($disp_int){
           case 1:
               $text = "in stock";
               break;
           case 2:
               $text = "out of stock";
               break;
           case 3:
               $text = "available for order";
               break;
       }

       //Almacena el resultado
       $this->available = $text;
    }

    /**
     * Comprueba el parametro y retorna el identificador de "disponibilidad" para MySQL.
     */
    function set_text_available($disp_string){
        //Almacena el texto del string
        $this->available = $disp_string;
        //Variable
        $id = 0;
        //Distintos casos de acuerdo a la variable.
        switch($disp_string){
            case "in stock":
                $id = 1;
                break;
            case "out of stock":
                $id = 2;
                break;
            case "available for order":
                $id = 3;
                break;
        }

        //Almacena el resultado
        $this->id_available = $id;
    }

    /**
     * Comprueba el parametro y retorna el identificador de "estado" para MySQL.
     */
    function set_text_condition($cond_string){
        //Almacena el texto del string
        $this->condition = $cond_string;
        //Variable
        $id = 0;
        //Distintos casos de acuerdo a la variable.
        switch($cond_string){
            case "new":
                $id = 1;
                break;
            case "used":
                $id = 2;
                break;
        }
        //Almacena el resultado
        $this->id_condition = $id;
    }

    function set_link_image($linkimage){
        $this->link_image = $this->convertir_link_a_google_drive($linkimage);
    }

    function set_link_additional_image($linkextra){
        $this->link_additional_image = $this->convertir_additional_link_a_google_drive($linkextra);
    }

    function set_marca($marca){
        $this->marca = $marca;
    }

    /*
    * GETERS
    */

    function get_codebar(){
        return $this->codebar;
    }

    function get_title(){
        return $this->title;
    }

    function get_description(){
        return $this->description;
    }

    function get_price(){
        return $this->price;
    }

    function get_categoria(){
        return $this->categoria;
    }

    function get_id_categoria(){
        return $this->id_categoria;
    }

    function get_subcategoria(){
        return $this->subcategoria;
    }

    function get_text_condition(){
        return $this->condition;
    }

    function get_id_condition(){
        return $this->id_condition;
    }

    function get_text_available(){
        return $this->available;
    }

    function get_id_available(){
        return $this->id_available;
    }

    function get_link_page(){
        return $this->link_page;
    }

    function get_link_image(){
        return $this->link_image;
    }

    function get_link_additional_image(){
        return $this->link_additional_image;
    }

    function get_array_link_additional_image(){
        return $this->array_link_additional;
    }
    
    function get_marca(){
        return $this->marca;
    }

    /*
    function cargar_array_csv_v1($array, $id_cat){
        $this->codebar = $array[0];
        $this->title = $this->_limpiarTexto(utf8_encode($array[1]));
        $this->description = $this->_limpiarTexto(utf8_encode($array[2]));
        $this->price = utf8_encode($array[7]);
        $this->id_categoria = (int) utf8_encode($id_cat);
        //$this->link_page = utf8_encode($array[8]);
        $this->link_image = utf8_encode($array[9]);
        $this->link_additional_image = utf8_encode($array[10]);
        $this->marca = utf8_encode($array[11]);
        $this->set_text_condition($array[6]);
        $this->set_text_available($array[5]);
    }*/

    function cargar_array_csv($array, $id_cat){
        $this->codebar = $array[0];
        $this->title = (($array[1]));
        $this->description = (($array[2]));
        $this->price = ($array[7]);
        $this->id_categoria = (int) ($id_cat);
        //$this->link_page = ($array[8]);
        $this->link_image = ($array[9]);
        $this->link_additional_image = ($array[10]);
        $this->marca = ($array[11]);
        $this->set_text_condition($array[6]);
        $this->set_text_available($array[5]);
    }

    protected function _limpiarTexto($str){
        $texto = str_replace("Ã¡", "á", $str); //a
        $texto = str_replace("Ã±", "ñ", $texto); //ñ
        $texto = str_replace("", "•", $texto); //Caracter que no reconozco
        $texto = str_replace("*", "•", $texto); //•
        return $texto;
    }

    /**
     * Convierte un link de google drive a su equivalente para los sitios web.
     * Caso 1: Devuelve null si el link es invalido.
     * Caso 2: Devuelve el mismo link en caso de que comience de una determinada manera.
     * Caso 3: Devuelve el link procesado en caso de que el link comience con una determinada manera.
     */
    protected function convertir_link_a_google_drive($link_google){
        //Cadena a retornar
        $str_return = null;
        //Si el link es del estilo "https://drive.google.com/file/d/codigo/view?usp=sharing"
        if (str_contains($link_google, "https://drive.google.com/file/d/")){
            $array = explode("/", $link_google);
            $str_return = "https://drive.google.com/uc?export=view&id=".$array[5];
        }
        else{
            //Si el link es del estilo "https://drive.google.com/uc?export=view&id="
            if (str_contains($link_google, "https://drive.google.com/uc?export=view&id=")){
                $str_return = $link_google;
            }
            else{
                //Cualquier otro link es nula su transformacion
                $str_return = null;
            }
        }
        return $str_return;
    }

    /**
     * Recibe una cadena compuesta por uno o mas links separados por "," y realiza la conversión a
     * links de Google Drive y lo retorna como un unico string.
     */
    protected function convertir_additional_link_a_google_drive($str_add_link){
        $str_sin_space = str_replace(" ", "", $str_add_link);
        $array_link = explode(",", $str_sin_space);

        $pos = 1;
        $total = count($array_link);
        
        $str_return = ($total>0) ? $array_link[0] : "";
        $this->array_link_additional[0] = ($total>0) ? $array_link[0] : "";
        
        while($pos<$total){            
            $str = $this->convertir_link_a_google_drive($array_link[$pos]);
            $this->array_link_additional[$pos] = $str;
            $str_return = $str_return.",".$str;
            $pos = $pos + 1;
        }

        return $str_return;
    }
    
    function set_cat_product_facebook($cat){
        $this->cat_product_facebook = $cat;
    }

    function set_cat_product_google($cat){
        $this->cat_product_google = $cat;
    }

    function get_cat_product_facebook(){
        return $this->cat_product_facebook;
    }

    
    function get_cat_product_google(){
        return $this->cat_product_google;
    }

    /**
     * Devuelve un arreglo listo para ser insertado en un archivo csv.
     */
    function get_to_string_export(){
        //'id','title','description','google_product_category','fb_product_category',
        //'availability','condition','price','link','image_link','additional_image_link','brand'
        $array_to_string = array();
        array_push($array_to_string, $this->codebar);
        array_push($array_to_string, $this->title);
        array_push($array_to_string, "'".$this->description."'");
        array_push($array_to_string, $this->cat_product_google);
        array_push($array_to_string, $this->cat_product_facebook);
        array_push($array_to_string, $this->available);
        array_push($array_to_string, $this->condition);
        array_push($array_to_string, $this->price);
        array_push($array_to_string, $this->link_page);
        array_push($array_to_string, $this->link_image);
        array_push($array_to_string, $this->link_additional_image);
        array_push($array_to_string, $this->marca);

        //$cadena_export = '"'.$array_to_string[0].'","'.$array_to_string[1].'","'.$array_to_string[2].'","'
        // .$array_to_string[3].'","'.$array_to_string[4].'","'.$array_to_string[5].'","'
        //.$array_to_string[6].'","'.$array_to_string[7].' ARS","'.$array_to_string[8].'","'
        //.$array_to_string[9].'","'.$array_to_string[10].'","'.$array_to_string[11].'"';

        //return $cadena_export;
        return $array_to_string;
    }
}
?>