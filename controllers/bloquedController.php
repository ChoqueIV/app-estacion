<?php 

    // crea el objeto con la vista
    $tpl = new Kiwi("bloqued");

    $vars = ["MSG_BLOQUED_ERROR" => ""];
    
    $user = new User();        

    $token = explode("=", $_SERVER["REQUEST_URI"])[1];

    $result = $user->blocked($token);

    $vars = ["MSG_BLOQUED_ERROR" => $result["error"]];
    
    $tpl->loadTPL();

    $tpl->setVarsTPL($vars);

    $tpl->printTPL();
    
 ?>