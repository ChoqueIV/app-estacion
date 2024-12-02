<?php 

	$users = new User();

	$tpl = new Kiwi("login");

	$tpl->loadTPL();

	$tpl->setVarsTPL($vars);

	$tpl->printTPL();

 ?>