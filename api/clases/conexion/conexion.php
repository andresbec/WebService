<?php

$usuario_bd = "XXX";
$contrasena_bd = "XXX";
// Conexion QA
$conexion = "(DESCRIPTION = 
				(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = )(Port = )))
				(CONNECT_DATA = (SID = ))
			)";
			
// Conexion PROD
/*$conexion = "(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST =  )(PORT = ))
			    (CONNECT_DATA = (SID = ))
			)";*/	

function query_oracle($nitcliente)
{
	//echo "Conexion exitosa<br>";	
	global $usuario_bd, $contrasena_bd,	$conexion;
	
	$conn = oci_connect( $usuario_bd, $contrasena_bd, $conexion, "AL32UTF8");
		if (!$conn){
			$e = oci_error();
			return trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		else {
			$sql = 'BEGIN P_CONSULTA_NIT_WS(:p_nit, :p_cur_nit, :p_codigo, :p_respuesta); END;';
			$stid = oci_parse($conn, $sql);
			oci_bind_by_name($stid, ':p_nit', $nitcliente, 15, SQLT_CHR);
			oci_bind_by_name($stid, ':p_codigo', $p_codigo, 100);
			oci_bind_by_name($stid, ':p_respuesta', $p_respuesta, 1000);
			
			$cursor_cliente = oci_new_cursor($conn);
			oci_bind_by_name($stid, ":p_cur_nit", $cursor_cliente, -1, OCI_B_CURSOR);
			
			oci_execute($stid) or die ("Unable to execute query");
			oci_free_statement($stid);
			
			if ($p_codigo== '200'){
				oci_execute($cursor_cliente);
				oci_fetch_all($cursor_cliente, $consulta, null, -1, OCI_FETCHSTATEMENT_BY_ROW);
				oci_free_statement($cursor_cliente);
				$out_respuesta = array($consulta);
			} else {
				$out_respuesta = array("codigo" => $p_codigo, "respuesta" => $p_respuesta); 
			}	
			oci_free_statement($cursor);
			oci_close($conn);
			
			$respuesta = $out_respuesta;
			return $respuesta;
		}
}

function nonquery_oracle($StrSql){
	global $usuario_bd, $contrasena_bd,	$conexion;
	
	$db=$conexion;
	if (!$db = @oci_connect($usuario_bd,$contrasena_bd, $db, "AL32UTF8")) {
			$error = ocierror();
			printf("CONNECT error: %s", $error["message"]);
			die();
		}
	if (!$stmt = @ociparse($db,$StrSql)) {
		$error = ocierror($db);
		printf("ociparse error: %s", $error["message"]);
	} 
	else {
		if (!@OCIexecute($stmt)) {
			$error = ocierror($stmt);
			printf ($StrSql ); 		
			printf("OCIEXECUTE Failure. Error was: %s\n", $error["message"]);
		}
	}
}

function login_query($StrSql){
	global $usuario_bd, $contrasena_bd,	$conexion;
	
	$db=$conexion;
	if (!$db = @oci_connect($usuario_bd,$contrasena_bd, $db, "AL32UTF8")) {
			$error = ocierror();
			printf("CONNECT error: %s", $error["message"]);
			die();
		}
	if (!$stmt = @ociparse($db,$StrSql)) {
		$error = ocierror($db);
		printf("ociparse error: %s", $error["message"]);
	} 
	else {
		if (!@OCIexecute($stmt)) {
			$error = ocierror($stmt);
			printf ($StrSql ); 		
			printf("OCIEXECUTE Failure. Error was: %s\n", $error["message"]);
		}
		else{	
			while ($row = oci_fetch_array($stmt, OCI_ASSOC+OCI_RETURN_NULLS)) {
				$usuario = $row["USUARIO"];
				$password = $row["PASSWORD"];
			}
		}
	}
		return $usuario;
}
?>