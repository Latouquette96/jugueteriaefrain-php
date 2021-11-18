<?php

include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/html-class/plantilla-class.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/categoria_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/producto_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/disponibilidad_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/database-class/condicion_mysql.php";
include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/inc/class/producto/producto.php";


class ABMSProductClass extends PlantillaHTMLPHP{
    //Atributos de instancia
    protected $flag_codebar_on;
    protected $flag_is_form;
    protected $producto;

    function ABMSProductClass(){
        parent:: __construct();
    }

    /**
     * Construye el html.
     * $flag_codebar_on: True/False indica si aparece o no el formulario de busqueda por codebar.
     * $flag_is_form: True/False indica si es un formulario o una 'card' con informacion.
     */
    function construir_html($title, $flag_codebar_on, $flag_is_form){
        $this->flag_is_form = $flag_is_form;
        $this->flag_codebar_on = $flag_codebar_on;
        //Objeto producto
        $this->producto = new Producto();

        $this->_construir_inicio_header();
        $this->_construir_title($title);
        $this->_construir_bloques_css();
        $this->_construir_fin_header();
        $this->_construir_inicio_body();
        $this->_construir_nav();

        if ($title!=null && $title!=""){
            echo "<h2 id='titulo' name='titulo'>$title</h2>";
        }
        $this->_construir_abms_product();

        $this->_construir_fin_body();
        $this->_construir_fin_html();
    }

    /**
     * Construye el abms_product.
     * Si $flag_codebar_on está activada, deberá mostrar un formulario de busqueda por código de barra.
     * Si $flag_is_form está activada, deberá mostrar un formulario para un producto, en caso contrario
     *  mostrará una 'card' con los datos del producto.
     */
    protected function _construir_abms_product(){
        //Si $flag_codebar_on = True, entonces se crea el formulario codebar.
        if ($this->flag_codebar_on){
            $this->_construir_buscador_producto();

            //Si tiene un valor para la variable POST, entonces cargar producto
            //y construir form producto.
            if (isset($_POST['sp_codebar_search'])){
                $codebar_search = (int) $_POST['sp_codebar_search'];
                $this->_set_producto($codebar_search);
                $this->_construir_form_producto();
            }
        }
        else{
            //construir form producto.
            $this->_construir_form_producto();
        }   
    }

    /**
     * Busca el producto por su codigo de barra y lo setea en $this->producto.
     */
    protected function _set_producto($codebar_search){
        $prod_mysql = new ProductoMySQL("Latouquette96", "39925523");
        $this->producto = $prod_mysql->search($codebar_search);
    }

    /**
     * Construye el formulario de busqueda.
     */
    protected function _construir_buscador_producto(){
        echo "<div class='container' id='div-search'>";
            echo "<legend class='col-form-label-md'>Buscar producto por código de barra</legend>";
            echo "<form action='' method='POST'>";
                $this->_set_codebar_search();
                $this->_set_button_search();
            echo "</form>";
        echo "</div>";
    }

    protected function _set_codebar_search(){
        $codebar_search = (isset($_POST['sp_codebar_search'])) ? (int) $_POST['sp_codebar_search'] : 0;

        echo "<div class='form-group'>";
        echo "<input type='number' class='form-control' id='sp_codebar_search' name='sp_codebar_search' placeholder='Código de barras a buscar' value=".$codebar_search."></input>";
        echo "</div>";
    }

    protected function _set_button_search(){
        echo "<div class='form-group row'>";
        echo "<input name='btn_search' id='btn_search' class='btn btn-primary' type='submit' value='Cargar...'></input>";
        echo "</div>";
    }

    /**
     * Construye el formulario de busqueda.
     */
    protected function _construir_form_producto(){
        echo "<div class='container'>";
            echo "<legend class='col-form-label-md'>Datos del producto</legend>";
            echo "<form action='' method='POST'>";
                
                if($this->flag_codebar_on){
                    $codebar_search = (int) $_POST['sp_codebar_search'];
                    echo "<input type='hidden' id='num_codebar_search' name='num_codebar_search' value=".$codebar_search."></input>";
                }

                $this->_set_select_categorias();
                $this->_set_codebar();
                $this->_set_title_and_brand();
                $this->_set_price();
                $this->_set_description();
                $this->_set_select_condition_and_available();
                $this->_set_link_page();
                $this->_set_link_image();
                $this->_set_link_image_additional();
                $this->_set_button_submit();

            echo "</form>";
        echo "</div>";
    }

    /**
     * Dibujar listado de categorias.
     */
    protected function _set_select_categorias(){
        //Objeto categoria_mysql
        $obj_cat = new CategoriaMySQL("Latouquette96","39925523");

        //Campo de código de barra
        echo "<div class='form-row align-items-center'>";
            echo "<div class='col-auto'>";
                echo "<label for='select_categoria'>Categoria</label>";
                echo "<select id='select_categoria' name='select_categoria' ".$this->_get_class_state_form_control().">";

                    //Identificador de categoria
                    $id_cat = (int) $this->producto->get_id_categoria();
                    //Arreglo de categorias
                    $array_categorias = $obj_cat->get_array_categorias_subcategorias();
                    //Obtiene el producto seleccionado
                    $cat_select = $array_categorias[$id_cat];
                    
                    //Establece como seleccionada la categoria encontrada.
                    //Si el valor es 0, entonces se la marca como desabilitada y seleccionada
                    echo "<option value=".((int) $cat_select[0])." ".(($id_cat==0) ? "selected disabled" : "selected").">"
                        .$cat_select[1]."</option>";

                    //Remueve la categoria seleccionada del arreglo
                    unset($array_categorias[$id_cat]);

                    //Recorre el arreglo de categorias y lo inserta como opcion
                    foreach($array_categorias as $dato){
                        $categ = $dato;
                        echo "<option value=".((int) $categ[0]).">".$categ[1]."</option>";                           
                    }

                echo "</select>";
            echo "</div>";
        echo "</div>";
    }

    /**
     * Dibujar cuadro de texto de código de barras.
     */
    protected function _set_codebar(){
        //Campo de código de barra
        echo "<div class='form-row align-items-center'>";
        echo "<div class='col-auto'>";
            echo "<label for='sp_codebar'>Código de barra</label>";
            echo "<input type='number' ".$this->_get_class_state_form_control()." id='sp_codebar' name='sp_codebar' placeholder='Código de barras' value='".$this->producto->get_codebar()."'>";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Dibuja el cuadro de texto para el titulo y la marca.
     */
    protected function _set_title_and_brand(){
        //Fila Titulo-marca
        echo "<div class='form-row align-items-center'>";
        //Título
        echo "<div class='col-auto'>";
            echo "<label for='txt_title'>Titulo</label>";
            echo "<input type='text' ".$this->_get_class_state_form_control()." id='txt_title' name='txt_title' placeholder='Titulo del producto' value='".$this->producto->get_title()."'>";
        
            echo "</div>";
        //Marca
        echo "<div class='col-auto'>";
            echo "<label for='txt_marca'>Marca</label>";
            echo "<input type='text' ".$this->_get_class_state_form_control()." id='txt_marca' name='txt_marca' placeholder='Marca del producto' value='".$this->producto->get_marca()."'>";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Dibuja un cuadro de texto para la descripcion.
     */
    protected function _set_description(){
        //Fila Descripcion
        echo "<div class='form-row align-items-center'>";
        //Título
        echo "<div class='col-auto'>";
            echo "<label for='txt_descript'>Descripción</label>";
            echo "<textarea ".$this->_get_class_state_form_control()." id='txt_descript' name='txt_descript' rows=10 placeholder='Descripcion del producto'>".$this->producto->get_description()."</textarea>";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Dibujar cuadro de texto de precio.
     */
    protected function _set_price(){
        //Campo del precio
        echo "<div class='form-row align-items-center'>";
        echo "<div class='col-auto'>";
            echo "<label for='sp_price'>Precio $</label>";
            echo "<input type='number' ".$this->_get_class_state_form_control()." id='sp_price' name='sp_price' step='0.5' placeholder='Precio ARS' value=".$this->producto->get_price().">";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Dibujar listado de condiciones y disponibilidad del producto.
     */
    protected function _set_select_condition_and_available(){
        //Objeto condicion_mysql
        $obj_cond = new CondicionMySQL("Latouquette96","39925523");
        //Objeto disponibilidad_mysql
        $obj_disp = new disponibilidadMySQL("Latouquette96","39925523");

        //Campo de código de barra
        echo "<div class='form-row align-items-center'>";
            echo "<div class='col-auto'>";
                echo "<label for='select_cond'>Condicion</label>";
                echo "<select id='select_cond' name='select_cond' ".$this->_get_class_state_form_control().">";

                    //Identificador de condicion
                    $id_cond = (int) $this->producto->get_id_condition();
                    //Arreglo de condiciones
                    $array_condiciones = $obj_cond->get_array_condiciones();
                    //Obtiene el producto seleccionado
                    $cond_select = $array_condiciones[$id_cond];
                    
                    //Establece como seleccionada la condicion encontrada.
                    //Si el valor es 0, entonces se la marca como desabilitada y seleccionada
                    echo "<option value=".((int) $cond_select[0])." ".(($id_cond==0) ? "selected disabled" : "selected").">"
                        .$cond_select[1]."</option>";

                    //Remueve la condicion seleccionada del arreglo
                    unset($array_condiciones[$id_cond]);

                    //Recorre el arreglo de condiciones y lo inserta como opcion
                    foreach($array_condiciones as $dato){
                        $cond = $dato;
                        echo "<option value=".((int) $cond[0]).">".$cond[1]."</option>";                           
                    }

                echo "</select>";
            echo "</div>";

            echo "<div class='col-auto'>";
                echo "<label for='select_disp'>Disponibilidad</label>";
                echo "<select id='select_disp' name='select_disp' ".$this->_get_class_state_form_control().">";

                    //Identificador de disponibilidade
                    $id_disp = (int) $this->producto->get_id_available();
                    //Arreglo de disponibilidades
                    $array_disponibilidades = $obj_disp->get_array_disponibilidad();
                    //Obtiene el producto seleccionado
                    $disp_select = $array_disponibilidades[$id_disp];
                    
                    //Establece como seleccionada la disponibilidades encontrada.
                    //Si el valor es 0, entonces se la marca como desabilitada y seleccionada
                    echo "<option value=".((int) $disp_select[0])." ".(($id_disp==0) ? "selected disabled" : "selected").">"
                        .$disp_select[1]."</option>";

                    //Remueve la disponibilidades seleccionada del arreglo
                    unset($array_disponibilidades[$id_disp]);

                    //Recorre el arreglo de disponibilidades y lo inserta como opcion
                    foreach($array_disponibilidades as $dato){
                        $disp = $dato;
                        echo "<option value=".((int) $disp[0]).">".$disp[1]."</option>";                           
                    }

                echo "</select>";
            echo "</div>";
        echo "</div>";
    }

    /**
     *  Crea un objeto de texto oculto con el link de la pagina.
     */
    protected function _set_link_page(){
        echo "<input type='hidden' id='txt_link_page' name='txt_link_page' value='".$this->producto->get_link_page()."'></input>";
    }

    /**
     * Dibuja un link de imagen con un lienzo donde se muestra la imagen.
     */
    protected function _set_link_image(){
        echo "<div class='form-row align-items-center'>";

            echo "<div class='col-auto'>";
                echo "<label for='txt_link_image'>Link de imagen</label>";
                echo "<textarea ".$this->_get_class_state_form_control()." id='txt_link_image' name='txt_link_image' 
                    onchange='update_image()' rows=3 placeholder='https://drive.google.com/file/d/codigo/view?usp=sharing'>".$this->producto->get_link_image()."</textarea>";
            echo "</div>"; //Fin form-group

            echo "<div id='div-image-principal' class='col-auto'>";
                echo "<div class='carousel'>";
                    echo "<div id='carouselExampleControls' class='carousel slide' data-ride='carousel'>";
                        echo "<div class='carousel-inner'>";
                            echo "<div class='carousel-item active'>";
                                echo "<img src='".$this->producto->get_link_image()."' id='img-link' class='img-fluid img-thumbnail' />";
                            echo "</div>"; //Fin carousel-item
                        echo "</div>"; //carousel-inner
                    echo "</div>"; //carouselExampleControls
                echo "</div>"; //carousel
            echo "</div>"; //Fin form-group

        echo "</div>";
    }

    /**
     * Establece el textarea con los links de imagen extra.
     */
    protected function _set_link_image_additional(){
        echo "<div class='form-row align-items-center'>";
            echo "<div class='col-auto'>";
                echo "<label for='txt_link_image_extra'>Link de imagen extra</label>";
                echo "<textarea ".$this->_get_class_state_form_control()." id='txt_link_image_extra' name='txt_link_image_extra' 
                    onchange='actualizar_carousel()' rows=10 placeholder='https://drive.google.com/file/d/codigo/view?usp=sharing'>".$this->producto->get_link_additional_image()."</textarea>";
             echo "</div>";

            echo "<div id='div-imagenes-extra' class='col-auto'>";
                $array_links = $this->producto->get_array_link_additional_image();
                $long_array = count($array_links);
                //crea el carruzel
                echo "<div class='carousel'>";
                    echo "<div id='carouselExampleControls' class='carousel slide' data-ride='carousel'>";
                        echo "<div class='carousel-inner'>";
                            echo "<div id='imagen_carousel'>";
                                echo "<div id='div_contenedor'>";
                                    echo "<div id='div_item_active' class='carousel-item active'>";
                                        echo "<img src='".(($long_array>0) ? $array_links[0] : "")."' class='d-block w-100 img-fluid img-thumbnail' alt='...'>";
                                    echo "</div>";

                                    $pos = 1;
                                    while($pos<$long_array){
                                        echo "<div id='div_item' class='carousel-item'>";
                                            echo "<img src='".$array_links[$pos]."' class='d-block w-100 img-fluid img-thumbnail' alt='...'>";
                                        echo "</div>";
                                        $pos = $pos + 1;
                                    }

                                echo "</div>";//Fin contenedor.
                            echo "</div>";//Fin imagen
                        echo "</div>";//Fin carousel-inner
                    echo "</div>";//carouselExampleControls
                echo "</div>";//Fin carousel
            echo "</div>";//col-auto
        echo "</div>";//Fin div-row
    }


    /**
     * Comprueba el estado de $flag_codebar_on y $flag_is_form y devuelve el texto que debe contener los objetos
     * del formulario.
     */
    protected function _get_class_state_form_control(){
        $to_return = "";

        //Alternativa 1: Producto para editar.
        //Alternativa 2: Producto para agregar.
        if (($this->flag_codebar_on && $this->flag_is_form) || ($this->flag_is_form)){
            $to_return = "class='form-control'";
        }
        else{
            //Producto para eliminar.
            //Producto para buscar.
            if ($this->flag_codebar_on){
                $to_return = "readonly class='form-control-plaintext'";
            }
        }
        
        return $to_return;
    }

    /**
     * Establece el boton submit del formulario.
     */
    protected function _set_button_submit(){
        echo "<div class='form-group row'>";
            echo "<input id='btn-next' name='btn-next' class='btn btn-primary' type='submit' value='Confirmar'></input>";
        echo "</div>";
    }

    /**
     * Muestra un cuadro con el resultado de la operación.
     */
    function set_resultado(){
        echo "<div class='alert alert-success' role='alert'>";
            echo "¡Operacion realizada con exito!";
        echo "</div>";
    }
}
?>

<script type = "text/javascript">

    function actualizar_carousel(){
        //Remueve el div contenedor de los carousel.
        var doc_remove = document.getElementById("div_contenedor");
        var div_imagen_carousel = document.getElementById("imagen_carousel");

        div_imagen_carousel.removeChild(doc_remove);

        //Crea un nuevo elemento div_contenedor
        var caja_contenedor = document.createElement("div");
        caja_contenedor.setAttribute("id", "div_contenedor");

        //CONTROL DE VARIABLES CON RESPECTO A LAS IMAGENES
        
        //Recupera los enlaces de imagen del textarea 
        var str = document.getElementById("txt_link_image_extra").value;
        var str_link = str.split(',');

        //Inicializa variables.
        var total = str_link.length;
        //Convierte el link a Google drive;
        str_link[0] = (total>0) ? convertir_link_a_google_drive(str_link[0]) : "";
        //Almacena el texto en una cadena.
        var txt_link_image_extra = str_link[0];
        var i = 1;
        //Para cada link, se lo convierte a formato google drive y se lo concatena en str_links.
        while(i<total){
            str_link[i] = convertir_link_a_google_drive(str_link[i]);
            txt_link_image_extra = txt_link_image_extra + "," + str_link[i];
            i = i + 1;
        } 

        //CREACION DE LOS DIVS DE IMAGENES

        if (total>0){
            //Creacion de div_item_active
            var caja_div_item_active = document.createElement("div");
            caja_div_item_active.setAttribute("id", "div_item_active");
            caja_div_item_active.setAttribute("class", "carousel-item active");
            //echo "<img src='".$array_links[0]."' class='d-block w-100' alt='...'>";
            var img_active = document.createElement("img");
            img_active.setAttribute("src", str_link[0]);
            img_active.setAttribute("class", "d-block w-100 img-fluid img-thumbnail");
            caja_div_item_active.appendChild(img_active);
            caja_contenedor.appendChild(caja_div_item_active);

            i = 1;
            for (i=1; i<total; i++){
                //Creacion de div_item_active
                var caja_div_item = document.createElement("div");
                caja_div_item.setAttribute("id", "div_item");
                caja_div_item.setAttribute("class", "carousel-item");
                //echo "<img src='".$array_links[0]."' class='d-block w-100' alt='...'>";
                var img = document.createElement("img");
                img.setAttribute("src", str_link[i]);
                img.setAttribute("class", "d-block w-100 img-fluid img-thumbnail");
                caja_div_item.appendChild(img);
                caja_contenedor.appendChild(caja_div_item);
            }
        }

        div_imagen_carousel.appendChild(caja_contenedor);
        document.getElementById("txt_link_image_extra").value = txt_link_image_extra;
    }

    function update_image() {
        var link_original = document.getElementById("txt_link_image").value;
        var link_google = convertir_link_a_google_drive(link_original);
        document.getElementById("img-link").src = link_google; 
        document.getElementById("txt_link_image").value = link_google;
    }

    function convertir_link_a_google_drive(link_google){
        //Cadena a retornar
        var str_return = link_google;
        //Si el link es del estilo "https://drive.google.com/file/d/codigo/view?usp=sharing"
        if (link_google.includes("https://drive.google.com/file/d/")){
            var array_link = link_google.split("/");
            str_return = "https://drive.google.com/uc?export=view&id=" + array_link[5];
        }
        return str_return;
    }

</script>