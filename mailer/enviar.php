<?php
session_start();

/**
 * no mostrar mensajes de notificaci�n de errores de ejecuci�n del script si los hubiere
 */
error_reporting(0);

$sesion = new sesion($_SESSION["Key"], configuraciones::tiempoSesion);

/**
 * guardando informacion que ingresa a la base auditoria
 */
include '../auditoria/auditoria.php';

require_once '../class.phpmailer.php';
require_once '../Correo.php';

$mail = new PHPMailer(true);

$correo = new Correo();

$logica = new logica();

/**
 * verificar que se conecto con la base de datos
 * con exito
 */
if($logica->getError() > 0){
	echo Logs::mensajes($logica->getError());
	Logs::writeLogs('connect', mysqli_connect_error().' - '.$_SERVER['PHP_SELF']);
	exit();
}

$mail->IsSMTP();

try {
	$mail->SMTPDebug  = 0;
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "tls";
	$mail->Host       = $correo->getHost();
	$mail->Username   = $correo->getUser();
	$mail->Password   = $correo->getPass();

	$to = explode(';', trim($_POST['to']));
	foreach($to as $row){
		$row = trim($row);
		if(strlen($row)>3){
			$name = explode('@',$row);
			if($mail->ValidateAddress($row)){
				$mail->AddAddress($row, ($name[0]));
			}else{
				Logs::writeLogs('CEI', $row);//correo electronico invalido
			}
		}
	}
	
	$cc = explode(';', trim($_POST['cc']));
	foreach($cc as $row){
		$row = trim($row);
		if(strlen($row)>3){
			$name = explode('@',$row);
			if($mail->ValidateAddress($row)){
				$mail->AddCC($row, ($name[0]));
			}else{
				Logs::writeLogs('CEI', $row);//correo electronico invalido
			}
		}
	}
	
	$bcc = explode(';', trim($_POST['bcc']));
	foreach($bcc as $row){
		$row = trim($row);
		if(strlen($row)>3){
			$name = explode('@',$row);
			if($mail->ValidateAddress($row)){
				$mail->AddBCC($row, ($name[0]));
			}else{
				Logs::writeLogs('CEI', $row);//correo electronico invalido
			}
		}
	}
	
	if(strlen($_POST['ccot']) > 0){
		$cc = explode(';', trim($_POST['ccot']));
		foreach($cc as $row){
			$row = trim($row);
			if(strlen($row)>3){
				if($mail->ValidateAddress($row)){
					$name = explode('@',$row);
					$mail->AddCC($row, ($name[0]));
				}else{
					echo 'Mail invalido :'.$row;
					Logs::writeLogs('CEI', $row);//correo electronico invalido
					exit();
				}
			}
		}
	}
	
	$mail->AddBCC($correo->getCorreo(), $correo->getNom());
	$mail->SetFrom($correo->getCorreo(), $correo->getNom());

	$mail->Subject = utf8_decode($_POST['subject']);
	
	$Eve_Int = $sesion->desencriptar($_POST['codigo']);
	
	$datosEvento = $logica->getDatosEvento($Eve_Int);
	$datosCamaras = $logica->getCamarasEvento($Eve_Int);
	$datosArchivos = $logica->getArchivosEvento($Eve_Int);
	
	/**
	 * mostrar usuario de archivos
	 */
	$archivos = true;
	
	/**
	 * cambiar logo
	 */
	$bLogo = true;
	
	/**
	 * obtener el cuerpo del correo a enviar
	 */
	include 'notificacion.php';
	
	$mail->MsgHTML($html);
	
	if($mail->Send() != false){
		$data['success'] = '"success":true';
		$logica->iniciarTransaccion();
		$logica->addEventocorreos($Eve_Int, 'E', trim($_POST['to']), trim($_POST['cc']).trim($_POST['ccot']), trim($_POST['bcc']), $sesion->__get('codigo'));
		if(!$logica->finalizarTransaccion()){
			Logs::writeLogs('BD', $logica->getErrorMsg());
			Logs::writeLogs('envio de notificaci�n evento', trim($_POST['to']).trim($_POST['cc']).trim($_POST['ccot']).trim($_POST['bcc']).$sesion->__get('codigo').$_SERVER['PHP_SELF']);
			echo $logica->getErrorMsg();
			exit();
		}
	}else{
		Logs::writeLogs('smtp', $mail->ErrorInfo);
		echo $mail->ErrorInfo;
	}
} catch (phpmailerException $e) {
	echo utf8_encode('Error al enviar Notificaci�n');
	Logs::writeLogs('smtp', $e->getMessage());
	exit();
} catch (Exception $e) {
	echo utf8_encode('Error al enviar Notificaci�n');
	Logs::writeLogs('smtp', $e->getMessage());
	exit();
}
echo json_encode($data);