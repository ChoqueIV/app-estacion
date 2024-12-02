<?php

// incluimos a User para poder hacer uso de la variable cargada en session
include_once 'models/User.php';

// Inicia la sesión
session_start();

include_once 'env.php';

include 'lib/mp-mailer-master/Mailer/src/PHPMailer.php';
include 'lib/mp-mailer-master/Mailer/src/SMTP.php';
include 'lib/mp-mailer-master/Mailer/src/Exception.php';

// motor de plantillas
include 'lib/kiwi/Kiwi.php';  

// para pasar variables a las plantillas
$vars = [];

// por defecto se va a landing
$controlador = "landing";

// si pidieron una sección lo llevamos a ella
if (strlen($_GET['slug']) != 0) {
    $controlador = $_GET['slug'];    
}

// Verificamos si el archivo del controlador existe
if (!is_file('controllers/'.$controlador.'Controller.php')) {
    $controlador = "error404"; // Si el controlador no existe, redirigimos a error404
}

//=== firewall

// Listas de acceso dependiendo del estado del usuario
$controlador_login = ["landing", "panel", "detalle", "logout", "bloqued", "login", "reset"];  // Secciones solo para usuarios logueados
$controlador_anonimo = ["landing", "panel", "login", "register", "validate", "bloqued", "recovery", "reset"]; // Secciones solo para usuarios no logueados

if (empty($_SESSION)) {

	if (in_array($controlador, $controlador_anonimo)) {
    	$controlador = $controlador;
    }else{
    	$controlador = 'error404';
    }

} else {
	if (in_array($controlador, $controlador_login)) {
    	$controlador = $controlador;
    }else{
    	$controlador = 'error404';
    }
}

include 'controllers/'.$controlador.'Controller.php';

?>