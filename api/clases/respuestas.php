<?php

function responseInvalidCredentials(){
	$respuesta = array();
	$respuesta["codigo"] = "401";
	$respuesta["descripcion"] = "Error de acceso - credenciales inválidas";
	return $respuesta;
}

function responseInvalidRequest(){
	$respuesta = array();
	$respuesta["codigo"] = "400";
	$respuesta["descripcion"] = "Peticion Invalida";
	return $respuesta;
}

function responseBlank(){
	$respuesta = array();
	$respuesta["codigo"] = "101";
	$respuesta["descripcion"] = "Cliente no existe";
	return $respuesta;
}

?>