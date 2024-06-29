<?php
require_once 'vistas/modulos/config.php';
// Controladores
require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";


$plantilla = new ControladorPlantilla();
$plantilla -> ctrPlantilla();