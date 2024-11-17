<?php

include './mp-mailer-master/credenciales.php';
include './mp-mailer-master/Mailer/src/PHPMailer.php';
include './mp-mailer-master/Mailer/src/SMTP.php';
include './mp-mailer-master/Mailer/src/Exception.php';

function sendmail($destinatario, $motivo, $mensaje){
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0 ;
    $mail->Host = HOST_EMAIL;
    $mail->Port = PORT;
    $mail->SMTPAuth = SMTP_AUTH; //
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Username = REMITENTE;
    $mail->Password = PASSWORD;

    $mail->setFrom(REMITENTE, NOMBRE);
    $mail->addAddress($destinatario);

    $nombre = "Maproom";
    $valor = $destinatario;
    $fechaExpiracion = time() + 3600 * 24 * 30;
    $path = "/";
    setcookie($nombre, $valor, $fechaExpiracion, $path);

    $mail->isHTML(true);

    $mail->Subject = utf8_decode($motivo);
    $mail->Body = utf8_decode($mensaje);

    if(!$mail->send()){
        error_log("Mailer no se pudo enviar el correo!" );
		$body = array("errno" => 1, "error" => "No se pudo enviar.");
    }else{
		$body = array("errno" => 0, "error" => "Enviado con exito.");
	}
    return $body;
}
 
?>


