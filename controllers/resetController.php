<?php 

	$tpl = new Kiwi("reset");

	$usuario = new User();

	$tpl->loadTPL();

	// se pasan las variables a la vista
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();


?>
 