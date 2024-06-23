<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "perfectwedding";

$Conexion = new mysqli($host, $user, $pass, $db);

if(!$Conexion){
    echo "Conexion fallida";
}