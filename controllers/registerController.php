<?php 

	// crea el objeto con la vista
	$tpl = new Kiwi("register");

	// carga la vista
	$tpl->loadTPL();
	
	// se pasan las variables a la vista
	$tpl->setVarsTPL($vars);

	// imprime en pantalla la página
	$tpl->printTPL();


 ?>