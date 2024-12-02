<?php

// Borra todas las variables de sesión
session_unset();

// Elimina la sesión
session_destroy();

// Borra las cookies de sesión si es necesario
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

// Redirige al login
header("Location: login");
exit;
?>
