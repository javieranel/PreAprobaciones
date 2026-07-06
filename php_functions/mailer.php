<?php
//Import PHPMailer classes into the global namespace
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../php_functions/notification/received.php';

	require 'PHPMailer/PHPMailer.php';
	require 'PHPMailer/Exception.php';
	require 'PHPMailer/SMTP.php';

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Mailer = 'smtp';
	$mail->CharSet = 'utf-8';
	$mail->Host = 'smtp.office365.com';
	$mail->From = 'notificacion@melonesoilterminal.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'notificacion@melonesoilterminal.com';
	$mail->Password = 'n@x1LTm#';
	$mail->SMTPSecure = 'tls';
	$mail->Port = 587;
	$mail->AddAddress('javieranel0107@gmail.com');
	$mail->AddAddress('jtapia@melonesterminal.com');
	$mail->SMTPDebug  = 0; //Muestra las trazas del mail, 0 para ocultarla
	$mail->isHTML(true); // Set email format to HTML
	$mail->Subject = 'Requisicion # ';
	$mail->Body = '


    
</br>

Muchas gracias.
</br>

Saludos.
';







	
	/*if ($archivoName != "") {
		$mail->AddAttachment($archivoTemp, $archivoName);
	}*/


	if(!$mail->send()) {
		echo 'Message could not be sent.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
 		//echo 'Message has been sent';
	}

?>