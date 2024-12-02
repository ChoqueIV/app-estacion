<?php 

	/**
	* @file User.php
	* @brief Declaraciones de la clase User para la conexión con la base de datos.
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/

	// incluye la libreria para conectar con la db
	include_once 'DBAbstract.php';
	include_once 'Mailer.php';

	// se crea la clase User que hereda de DBAbstract
	class User extends DBAbstract{

		private $nameOfFields = array();

		/**
		 * 
		 * @brief Es el constructor de la clase User
		 * 
		 * Al momento de instanciar User se llama al padre para que ejecute su constructor
		 * 
		 * */
		function __construct(){
		
			// quiero salir de la clase actual e invocar al constructor
			parent::__construct();

			/**< Obtiene la estructura de la tabla */
			$result = $this->query('DESCRIBE users');

			foreach ($result as $key => $row) {
				$buff =$row["Field"];
				
				/**< Almacena los nombres de los campos*/
				$this->nameOfFields[] = $buff;

				/**< Autocarga de atributos a la clase */
				$this->$buff=NULL;
			}
			

		}

		/**
		 * 
		 * Hace soft delete del registro
		 * @return bool siempre verdadero
		 * 
		 * */
		function leaveOut(){

			$id = $this->id;
			$fecha_hora = date("Y-m-d H:i:s");

			$ssql = "UPDATE users SET delete_at='$fecha_hora' WHERE id=$id";

			$this->query($ssql);

			return true;
		}

		/**
		 * 
		 * Finaliza la sesión
		 * @return bool true
		 * 
		 * */
		function logout(){
			return true;
		}

		/**
		 * 
		 * Intenta loguear al usuario mediante email y contraseña
		 * @param array $form indexado de forma asociativa
		 * @return array que posee códigos de error especiales
		 * 
		 * */
		function getBrowser($user_agent) {
	        $browser = "Desconocido";
	        $browser_array = array(
	            '/msie/i'      => 'Internet Explorer',
	            '/firefox/i'   => 'Firefox',
	            '/chrome/i'    => 'Chrome',
	            '/safari/i'    => 'Safari',
	            '/opera/i'     => 'Opera',
	            '/edge/i'      => 'Edge',
	            '/trident/i'   => 'Internet Explorer 11',
	            '/mobile/i'    => 'Mobile Browser'
	        );

	        foreach ($browser_array as $regex => $value) {
	            if (preg_match($regex, $user_agent)) {
	                $browser = $value;
	            }
	        }
	        return $browser;
	    }

	    /**
	     * 
	     * Función para obtener el sistema operativo desde el User-Agent.
	     */
	    function getOperatingSystem($user_agent) {
	        $os_platform = "Desconocido";
	        $os_array = array(
	            '/windows nt 10/i'     => 'Windows 10',
	            '/windows nt 6.3/i'    => 'Windows 8.1',
	            '/windows nt 6.2/i'    => 'Windows 8',
	            '/windows nt 6.1/i'    => 'Windows 7',
	            '/windows nt 6.0/i'    => 'Windows Vista',
	            '/windows nt 5.2/i'    => 'Windows Server 2003/XP x64',
	            '/windows nt 5.1/i'    => 'Windows XP',
	            '/macintosh|mac os x/i' => 'Mac OS X',
	            '/iphone/i'             => 'iPhone',
	            '/ipod/i'               => 'iPod',
	            '/ipad/i'               => 'iPad',
	            '/android/i'            => 'Android',
	            '/linux/i'              => 'Linux',
	            '/unix/i'               => 'Unix',
	            '/bsd/i'                => 'BSD'
	        );

	        foreach ($os_array as $regex => $value) {
	            if (preg_match($regex, $user_agent)) {
	                $os_platform = $value;
	            }
	        }
	        return $os_platform;
	    }
		

	    function sendLoginEmail($user_ip, $user_os, $user_browser, $token) {
	        $correo = new Mailer();
	        $contenido = "
	        <html>
	        <head>
	            <style>
	                /* Reset de estilos generales */
	                * {
	                    margin: 0;
	                    padding: 0;
	                    box-sizing: border-box;
	                }
	                body {
	                    font-family: 'Arial', sans-serif;
	                    background-color: #000000;
	                    color: #ffffff;
	                    line-height: 1.6;
	                    padding: 20px;
	                }

	                /* Estilo de la carta */
	                .container {
	                    background-color: #333333;
	                    padding: 30px;
	                    border-radius: 8px;
	                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
	                    max-width: 600px;
	                    margin: 0 auto;
	                }

	                h1 {
	                    font-size: 2rem;
	                    color: #ffffff;
	                    text-align: center;
	                    margin-bottom: 20px;
	                }

	                .highlight {
	                    color: #4a90e2;
	                }

	                p {
	                    font-size: 1.2rem;
	                    color: #d1d1d1;
	                    margin: 15px 0;
	                }

	                ul {
	                    margin: 15px 0;
	                    padding-left: 20px;
	                    list-style-type: disc;
	                    color: #d1d1d1;
	                }

	                /* Botón */
	                .button {
	                    display: inline-block;
	                    text-decoration: none;
	                    font-size: 1.2rem;
	                    font-weight: bold;
	                    color: #fff;
	                    background: linear-gradient(90deg, #4a90e2, #1e88e5);
	                    padding: 12px 25px;
	                    border-radius: 25px;
	                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
	                    transition: transform 0.3s ease, box-shadow 0.3s ease;
	                    text-align: center;
	                    margin-top: 20px;
	                }

	                .button:hover {
	                    transform: translateY(-5px) scale(1.05);
	                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
	                    background: linear-gradient(90deg, #1e88e5, #4a90e2);
	                }

	            </style>
	        </head>
	        <body>
	            <div class='container'>
	                <h1>Se ha iniciado sesión en tu cuenta de <span class='highlight'>EcoClima</span></h1>
	                <p>Recientemente se ha detectado un inicio de sesión en tu cuenta con los siguientes detalles:</p>
	                <ul>
	                    <li><strong>Dirección IP:</strong> $user_ip</li>
	                    <li><strong>Sistema Operativo:</strong> $user_os</li>
	                    <li><strong>Navegador:</strong> $user_browser</li>
	                </ul>
	                <p>Si no fuiste tú, puedes bloquear tu cuenta haciendo clic en el siguiente botón:</p>
	                <a href='https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/bloqued?token=".$token."' class='button'>No fui yo, bloquear cuenta</a>
	            </div>
	        </body>
	        </html>
	        ";

	        $correo->send([
	            "destinatario" => "sebachoque03@gmail.com", 
	            "motivo" => "Recuperación de cuenta", 
	            "contenido" => $contenido
	        ]);
	    }

	    function login($form){
	        $username = $form['username'];
			$password = md5($form["password"]."morphyx");

	        // Usar consulta preparada para evitar inyección SQL
	        $stmt = $this->db->prepare("SELECT * FROM app_estacion__usuario WHERE email = ? AND contraseña = ?");
	        $stmt->bind_param("ss", $username, $password);
	        $stmt->execute();


	        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

	        if (empty($result)) {
	            return ["errno" => 404, "error" => "Acceso Invalido"];
	        }

	        $result = $result[0];

	        $token_user = $result['token'];

	        if ($result['activo'] == 0) {
	            return ["error" => "Su usuario aún no se ha validado, revise su casilla de correo", "errno" => 406];
	        }

	        if ($result['bloqueado'] == 1) {
	            return ["error" => "Su usuario está bloqueado, revise su email", "errno" => 407];
	        }

	        // Establece los valores del usuario en la sesión
	        $_SESSION["user"] = $result;

	       	// Obtener la IP, sistema operativo y navegador
	        $user_ip = $_SERVER['REMOTE_ADDR'];
	        $user_agent = $_SERVER['HTTP_USER_AGENT'];
	        $user_os = $this->getOperatingSystem($user_agent);
	        $user_browser = $this->getBrowser($user_agent);

	        // Guardar los datos en la sesión
	        $_SESSION['user_ip'] = $user_ip;
	        $_SESSION['user_os'] = $user_os;
	        $_SESSION['user_browser'] = $user_browser;

	        $this->sendLoginEmail($user_ip, $user_os, $user_browser, $result['token']);
	        
	        
	        return ["error" => "Acceso válido", "errno" => 200];
	    }


	    function blocked($token){		
			$result = $this->query("SELECT * FROM app_estacion__usuario WHERE token ='$token'");
			$username= $result [0]["email"];
			$token_user= $result [0]["token"];
			$this->sendBloquedEmail($username, $token_user);

			
			if($result != []){
				if($result [0]["token_action"] == "" AND  $result[0]["activo"] == 1){
					$email= $result [0]["email"];
					$bloqueado=1;
					$fecha_hora = date("Y-m-d H:i:s");
					$ssql = "UPDATE app_estacion__usuario SET  bloqueado = '$bloqueado', blocked_date = '$fecha_hora' WHERE  token = '$token'";
					$result = $this->query($ssql);
					
					return ["error" => "Usuario bloqueado, revise su correo electrónico", "errno" => 200];
				}
			}	
			return ["error" => "El token no corresponde a un usuario", "errno" => 400];
		}


		function sendBloquedEmail($email, $token){

			$correo = new Mailer();

			$cuerpo = '<!DOCTYPE html>
		<html lang="es">
		<head>
		    <meta charset="UTF-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		    <title>Información de Inicio de Sesión</title>
		<style>
		        * {
		    margin: 0;
		    padding: 0;
		    box-sizing: border-box;
		}

		body {
		    font-family: Arial, sans-serif;
		    background-color: #f5f5f5;
		    color: #333;
		    display: flex;
		    justify-content: center;
		    align-items: center;
		    height: 100vh;
		}

		.container {
		    background-color: white;
		    padding: 20px;
		    border-radius: 8px;
		    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		    width: 400px;
		    text-align: center;
		}

		h1 {
		    color: #6d1b7b;
		    font-size: 24px;
		    margin-bottom: 15px;
		}

		.info-table {
		    width: 100%;
		    margin-bottom: 20px;
		    border-collapse: collapse;
		}

		.info-table td {
		    padding: 8px;
		    border: 1px solid #ddd;
		}

		.info-table td:first-child {
		    font-weight: bold;
		    text-align: left;
		    width: 40%;
		}

		.alert-button {
		    background-color: #d9534f;
		    color: white;
		    padding: 10px 20px;
		    border: none;
		    border-radius: 5px;
		    font-size: 16px;
		    cursor: pointer;
		}

		.alert-button:hover {
		    background-color: #c9302c;
		}

		p {
		    margin-bottom: 15px;
		}
		    </style>

		</head>
		<body>
		    <div class="container">
		        <h1>Información para desbloquear email</h1>
		        <p>Si no fuiste tú quien inició sesión y deseas cambiar el password :</p>

		 <a href="https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/reset?token=34234" class="button">Click aquí para cambiar contraseña</a>
				
		    </div>
		</body>
		</html>';

			$response = $correo->send([
				"destinatario" => $email,
				"motivo" => "Recuperacion de cuenta", 
				"contenido" => $cuerpo
			]);
		}


		function recovery($email) {
		    $result = $this->query("SELECT * FROM app_estacion__usuario WHERE email ='$email'");
		    if (empty($result)) {
				return ["error" => "El email ingresado no pertenece a la app", "errno" => 400];
		    }
		    $token_action = md5($_ENV['PROJECT_WEB_TOKEN'].$email);
		    $recupero = 1;

			$result = $this->query("UPDATE app_estacion__usuario SET  token_action = '$token_action', recupero = '$recupero' WHERE  email = '$email'");
			$this->sendRecoveryEmail($email, $token_action);
			return ["error" => "Solicitud Enviada Exitosamente", "errno" => 200];
		}


		function sendRecoveryEmail($email, $token_action) {
		    $correo = new Mailer();

		    // Cuerpo del correo en formato HTML
		    $cuerpo = '<!DOCTYPE html>
		<html lang="es">
		<head>
		    <meta charset="UTF-8">
		    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		    <title>Recuperación de Contraseña</title>
		</head>
		<body>
		    <div>
		        <h1>Solicitud de Recuperación de Contraseña</h1>
		        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no solicitaste este cambio, puedes ignorar este mensaje.</p>
		        <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
		        <a href="https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/app-estacion/reset?'.$token_action.'" target="_blank">Haz clic aquí para restablecer tu contraseña</a>
		    </div>
		</body>
		</html>';

		    // Enviar el correo utilizando la clase Mailer
		    $correo->send([
		        "destinatario" => $email,
		        "motivo" => "Recuperación de contraseña",
		        "contenido" => $cuerpo
		    ]);
		}

		function reset($password, $token){
			echo "HOla";
			echo $password;
			var_dump($password);

			exit();
			$result = $this->query("SELECT * FROM app_estacion__usuario WHERE token_action ='$token_action'");
			var_dump($result);
			exit();
		}



		/**
		 * 
		 * Agrega un nuevo usuario si no existe el correo electronico en la tabla users
		 * @param array $form es un arreglo assoc con los datos del formulario
		 * @return array que posee códigos de error especiales 
		 * 
		 * */

		// function sendActivationEmail($email, $token_email) {

	    //     $correo = new Mailer();
		//     $contenido = "
		//         <html>
		//         <head>
		//             <title>Bienvenido a EcoClima</title>
		//         </head>
		//         <body>
		//             <h1>¡Bienvenido a EcoClima!</h1>
		//             <p>Gracias por registrarte. Haz clic en el siguiente enlace para activar tu cuenta:</p>
		//             <a href='https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/validate?token=$token_email' style='padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; font-size: 18px;'>Click aquí para activar tu usuario</a>
		//             <p>Si no te has registrado, por favor ignora este mensaje.</p>
		//         </body>
		//         </html>
		//     ";
		// 	$correo->send([
	    //         "destinatario" => "sebachoque03@gmail.com", 
	    //         "motivo" => "Recuperación de cuenta", 
	    //         "contenido" => $contenido
	    //     ]);
		// }


		function register($form){
		    $email = $form["email"];
		    $password = md5($form["password"]."morphyx");

		    // Verificar si el email ya está registrado
		    $ssql = "SELECT count(*) FROM app_estacion__usuario WHERE email = '$email'";
		    $email_encontrados = $this->query($ssql);

		    if ($email_encontrados[0]["count(*)"] != 0) {
		        return ["error" => "Este email ya está registrado. Por favor, inicie sesión.", "errno" => 400];
		    }

		    // Generación de token único para el email
		    $token_email = md5($_ENV['PROJECT_WEB_TOKEN'].$email);
		    $token = md5($_ENV['PROJECT_WEB_TOKEN'].$email);

		    $ssql = "INSERT INTO app_estacion__usuario (token, email, contraseña, token_action) 
		    VALUES ('$token','$email','$password', '$token_email')";

		    $result = $this->query($ssql);

		    $this->id = $this->db->insert_id;

		    // Enviar un correo de bienvenida con el link de activación
      	    $this->sendRegisterEmail($email, $token_email);


		    return ["error" => "Usuario registrado. Revisa tu correo para activar tu cuenta.", "errno" => 200];
		}


		function sendRegisterEmail($email, $token) {
	        $correo = new Mailer();
	        $contenido = "
	        <html>
	        <head>
	            <style>
	                /* Reset de estilos generales */
	                * {
	                    margin: 0;
	                    padding: 0;
	                    box-sizing: border-box;
	                }
	                body {
	                    font-family: 'Arial', sans-serif;
	                    background-color: #000000;
	                    color: #ffffff;
	                    line-height: 1.6;
	                    padding: 20px;
	                }

	                /* Estilo de la carta */
	                .container {
	                    background-color: #333333;
	                    padding: 30px;
	                    border-radius: 8px;
	                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
	                    max-width: 600px;
	                    margin: 0 auto;
	                }

	                h1 {
	                    font-size: 2rem;
	                    color: #ffffff;
	                    text-align: center;
	                    margin-bottom: 20px;
	                }

	                .highlight {
	                    color: #4a90e2;
	                }

	                p {
	                    font-size: 1.2rem;
	                    color: #d1d1d1;
	                    margin: 15px 0;
	                }

	                ul {
	                    margin: 15px 0;
	                    padding-left: 20px;
	                    list-style-type: disc;
	                    color: #d1d1d1;
	                }

	                /* Botón */
	                .button {
	                    display: inline-block;
	                    text-decoration: none;
	                    font-size: 1.2rem;
	                    font-weight: bold;
	                    color: #fff;
	                    background: linear-gradient(90deg, #4a90e2, #1e88e5);
	                    padding: 12px 25px;
	                    border-radius: 25px;
	                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
	                    transition: transform 0.3s ease, box-shadow 0.3s ease;
	                    text-align: center;
	                    margin-top: 20px;
	                }

	                .button:hover {
	                    transform: translateY(-5px) scale(1.05);
	                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
	                    background: linear-gradient(90deg, #1e88e5, #4a90e2);
	                }

	            </style>
	        </head>
	        <body>
	            <div class='container'>
	                <h1>Se ha creado una cuenta en <span class='highlight'>EcoClima</span></h1>
	                <p>Activa tu cuenta haciendo clic en el siguiente botón:</p>
	                <a href='https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/validate?token=".$token."' class='button'>Activar Cuenta</a>
	            </div>
	        </body>
	        </html>
	        ";

	        $correo->send([
	            "destinatario" => $email, 
	            "motivo" => "Registro de cuenta", 
	            "contenido" => $contenido
	        ]);
	    }


		// Página que maneja la validación del token
		function validate($token_email) {

		    $ssql_check = "SELECT * FROM app_estacion__usuario WHERE token_action = '$token_email'";
		    $result_check = $this->query($ssql_check);

		    if (count($result_check) == 0) {
		        return ["error" => "Token inválido o caducado.", "errno" => 404];
		    }

		    $ssql_update = "UPDATE app_estacion__usuario SET activo = 1, token_action = '' WHERE token = '$token_email'";

		    $this->query($ssql_update);

		   	$this->sendValidateEmail($result_check[0]['email'], $result_check[0]['token']);

		    
		    if ($this->db->affected_rows > 0) {  // Usando $this->db->affected_rows
		        return ["error" => "Cuenta activada exitosamente.", "errno" => 200];
		    } else {
		        return ["error" => "Error al activar la cuenta. Inténtalo de nuevo.", "errno" => 500];
		    }
		}

		function sendValidateEmail($email, $token_email) {

	        $correo = new Mailer();
		    $contenido = "
		        <html>
		        <head>
		            <title>Bienvenido a EcoClima</title>
		        </head>
		        <body>
		            <h1>¡Bienvenido a EcoClima!</h1>
		            <p>Su cuenta ya se encuentra:</p>
		            <a href='https://mattprofe.com.ar/alumno/6811/ACTIVIDADES/app-estacion/login'>Click aquí para iniciar sesion</a>
		        </body>
		        </html>
		    ";
			$correo->send([
	            "destinatario" => $email, 
	            "motivo" => "Cuenta Activada", 
	            "contenido" => $contenido
	        ]);
		}



		/**
		 * 
		 * Actualiza los datos del usuario con los datos de un formulario
		 * @param array $form es un arregle asociativo con los datos a actualizar
		 * @return array arreglo con el código de error y descripción
		 * 
		 * */
		function update($form){
			$nombre = $form["txt_first_name"];
			$apellido = $form["txt_last_name"];
			$id = $this->id;


			$this->first_name = $nombre;
			$this->last_name = $apellido;

			$ssql = "UPDATE users SET first_name='$nombre', last_name='$apellido' WHERE id=$id";

			$result = $this->query($ssql);

			return ["error" => "Se actualizo correctamente", "errno" => 200];
		}

		/**
		 * 
		 * Cantidad de usuarios registrados
		 * @return int cantidad de usuarios registrados
		 * 
		 * */
		function getCantUsers(){

			$result = $this->query("SELECT * FROM users");

			return $this->db->affected_rows;
		}


		/**
		 * 
		 * @brief Retorna un listado limitado
		 * @param string $request_method espera a GET
		 * @param array $request [inicio][cantidad]
		 * @return array lista con los datos de los usuarios 
		 * 
		 * */
		function getAllUsers($request_method, $request){


			if($request_method!="GET"){
				return ["errno" => 410, "error" => "Metodo invalido"];
			}

			$inicio = 0;

			if(isset($request["inicio"])){
				$inicio = $request["inicio"];
			}

			if(!isset($request["cantidad"])){
				return ["errno" => 404, "error" => "falta cantidad por GET"];
			}

			$cantidad = $request["cantidad"];

			$result = $this->query("SELECT * FROM users LIMIT $inicio, $cantidad");

			return $result;
		}


	}

 ?>