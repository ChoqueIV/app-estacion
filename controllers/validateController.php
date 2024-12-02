<?php 
    

    // crea el objeto con la vista
    $tpl = new Kiwi("validate");

    $user = new User();

    $vars = ["MSG_VALIDATE_ERROR" => ""];
    
    $token = explode("=", $_SERVER["REQUEST_URI"])[1];

    $result = $user->validate($token);

    if ($result['errno'] == 200) {
        header("Location: login");
    }

    $vars = ["MSG_VALIDATE_ERROR" => $result["error"]];

    $tpl->loadTPL();

    $tpl->setVarsTPL($vars);

    $tpl->printTPL();
 ?>