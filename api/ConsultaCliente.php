<?php

require_once('clases/conexion/conexion.php');
require_once('clases/respuestas.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$nitcliente = $_POST['Nit'];
}

$StrSql = "SELECT * FROM USER_API WHERE USUARIO = '".$_SERVER['PHP_AUTH_USER']."' AND CONTRASENA = '". $_SERVER['PHP_AUTH_PW']."'";
$valid_username = login_query($StrSql);

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    header('HTTP/1.0 400 Invalid Request');
    $response = responseInvalidRequest();
	echo json_encode($response);
}
else if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER']== $valid_username && isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
					
	$response = query_oracle($nitcliente);
	echo json_encode($response);

} else {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    $response = responseInvalidCredentials();
	echo json_encode($response);
    exit;
}
?>