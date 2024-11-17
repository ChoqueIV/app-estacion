<?php

	// crea un usuario
	$users = new User();

	// crea el objeto con la vista
	$tpl = new Kiwi("panel");

	var_dump($_SESSION["morphyx"]['user']);
	// carga la vista
	$tpl->loadTPL();

	// reemplaza las variables en la vista
	$tpl->setVarsTPL($vars);

	// imprime en la página la vista
	$tpl->printTPL();

 ?>