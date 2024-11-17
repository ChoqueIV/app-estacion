<?php 

	$users = new User();

	$tpl = new Kiwi("login");

	// var_dump($_SESSION["morphyx"]['user']);

	// if ($_SERVER['REQUEST_METHOD']=='POST') {
	// 	if (!isset($_POST['btn-login'])) {
	// 		echo "Error";
	// 	}
	// 	$user = $_POST['username'];
	// 	$password = $_POST['password'];
	// 	$result = $users -> login($user, $password);
	// 	exit();
	// }

	$tpl->loadTPL();

	$tpl->setVarsTPL($vars);

	$tpl->printTPL();

 ?>