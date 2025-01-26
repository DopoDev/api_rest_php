<?php
//Definir los parametros para la conexión con la base de datos **BUSCAR MEJORA DE SEGURIDAD**
$host="localhost";
$user="root"; 
$pass= "";
$base_datos="api"; 

//Crear conexión con la base de datos
$conexion = new mysqli($host,$user,$pass,$base_datos);

//Si la conexión existe continua, si hay algún error procediendo ejecute el error con die.
if($conexion -> connect_error){
    die("Hubo un error en la conexion: " . $conexion->connect_error);
};

//Con esto establecemos el tipo de contenido que tendrá o devolverá la cabezerá será de HTTP a JSON. 
header("Content-Type: application/json"); 

//Un ejemplo de los procesos y resultados seria el siguiente codigo:
/*
$data = array(
    "nombre" => "Johao",
    "edad"=> 24,
    "ciudad"=> "Soledad" 
);

$data_json = json_encode($data);
echo $data_json;
*/


?>