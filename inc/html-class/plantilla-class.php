<?php

    /**
     * Clase PlantillaHTMLPHP. Guia de construccion:
     *  1. _construir_inicio_header();
     *  2. _construir_title($title);
     *  3. _construir_bloques_css();
     *  4. _construir_fin_header();
     *  5. _construir_inicio_body();
     *  6. _construir_nav();
     *  7. _construir_fin_body();
     *  8. _construir_fin_html();
    */
    class PlantillaHTMLPHP{

        function __construct(){}

        protected function _construir_inicio_header(){
            header("Content-Type: text/html;charset=utf-8");
            echo "<!DOCTYPE html>";
            echo "<html lang='es-AR'>";
            echo "<meta charset='UTF-8'/>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>";
            
        }

        protected function _construir_bloques_css(){
            //Bootstrap
            echo "<link rel='stylesheet' href='/jugueteriaefrain/css/bootstrap.min.css'>";
            //Mi estilos
            echo "<link href='/jugueteriaefrain/css/header.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/nav.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/body.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/font.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/carousel.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/form.css' rel='stylesheet' type='text/css'/>";
            echo "<link href='/jugueteriaefrain/css/img.css' rel='stylesheet' type='text/css'/>";
        }

        protected function _construir_title($title){
            echo "<title>Juguetería Efraín - ".$title."</title>";
        }

        protected function _construir_fin_header(){
            echo "</head>";
        }

        protected function _construir_inicio_body(){
            echo "<body>";
            //echo "<h1>Jugueteria Efrain</h1>";
        }

        protected function _construir_nav(){
            include_once $_SERVER['DOCUMENT_ROOT']."/jugueteriaefrain/mod/nav.php";
        }

        protected function _construir_fin_body(){

            echo "<script src='/jugueteriaefrain/js/jquery.min.js'></script>"; 
            echo "<script src='/jugueteriaefrain/js/popper.min.js'></script>";
            echo "<script src='/jugueteriaefrain/js/bootstrap.bundle.js'></script>";
            
            echo "</body>";
        }

        protected function _construir_fin_html(){
            echo "</html>";
        }
    }
?>