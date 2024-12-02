<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("recovery");

	// carga la vista
	$tpl->loadTPL();

	$vars = ["MSG_RECOVERY_ERROR" => ""];
	
	$usuario = new User();


	if(isset($_POST['btn-recovery'])){

		$email = $_POST['username'];

		$response = $usuario -> recovery($email);
		
		$vars = ["MSG_RECOVERY_ERROR" => $response["error"]];
	}


	// se pasan las variables a la vista
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();


?>
 