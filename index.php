<?php
include("db_header.php");

//Agregamos el metodo superglobal de SERVER el cual contiene información de los encabezados, rutas y ubicaciones de scripts. Se pueden obtener detalles del entorno y del cliente, en este caso obtenemos el metodo de la solicitud (GET, POST, PUT, DELETE...).
$metodo = $_SERVER['REQUEST_METHOD'];

//Imprimimos el metodo para saber que metodo es el que trae. 
//print_r($metodo . "\n");

//Primero definimos si una variable está definida con isset, _SERVER es un array con información, solicitamos la información de la ruta. Si el path está definida lo devuelve de lo contrario devuelve una /
$path = isset($_SERVER["PATH_INFO"])? $_SERVER["PATH_INFO"] :"/";
//La función explode dividira la cadena en un array usando un delimitador, en este caso /. 
$buscarId = explode('/', $path); 

//Si path es diferente a / busca el ultimo elemento del array, que deberia ser el id, de lo contrario asignará el valor null.
$id = ($path!=='/') ? end($buscarId):null; 

//Validaremos el metodo recibido para evaluar los verbos en el que cae.
switch($metodo){
    case 'GET':
        consultaSelect($conexion, $id); 
        break;
    case "POST":
        insertarConsulta($conexion);
        break;
    case "PUT":
        actualizarConsulta($conexion, $id);
        break;
    case "DELETE":
        eliminarConsulta($conexion, $id);
        break;
    default:
        echo "Metodo $metodo no permitido \n";
        break; 
};

//Creamos la función que recibe como parametro . Definimos la sentencia SQL para obtener los datos, y lo guardamos en un dato de resultados. 
function consultaSelect($conexion, $id){
    $sql = ($id===null)?"SELECT * FROM usuarios":"SELECT * FROM usuarios WHERE id='$id'";
    $resultado = $conexion->query($sql);

    //Definimos un condicional de que mientras exista el resultado agregue o cree un array de datos, posteriormente mientras existan datos para mostrar con la función fetch_assoc agregue los datos que definimos como fila en el array que se creó 
    if($resultado){
        $datos = array(); 
        //la funcion fetch_assoc se utiliza para obtener una fila de resultados de una columna SQL como un array asociativo
        while($fila = $resultado->fetch_assoc()){
            $datos[] = $fila;
        }
        echo json_encode($datos); 
    };
};

function insertarConsulta($conexion){
    //La función file_get_contents sirve para leer los datos del flujo de entrada HTTP. Se están obteniendo los datos del contenido enviador por metodo POST. La función json_decode("", true) convierte una cadena JSON en una estructura de datos php (Array u objeto), el true significa que será un array asociativo. 
    $dato = json_decode(file_get_contents("php://input"), true);
    $nombre = $dato['nombre'];  
    print_r($nombre); 

    $sql = "INSERT INTO usuarios(nombre) VALUES('$nombre')";
    $resultado = $conexion->query($sql); 

    if($resultado){
        $dato['id'] = $conexion->insert_id; 
        echo json_encode($dato);
    }else{
        echo json_encode(array('error: '=>'Error al crear usuario'));
    }
};

function eliminarConsulta($conexion, $id){
    echo "El id a borrar es: ". $id; 

    $sql = "DELETE FROM usuarios WHERE id='$id'";
    $resultado = $conexion->query($sql);
    if($resultado){
        echo json_encode(array("Mensaje: "=> "Usuario Eliminado"));
    }else{
        echo json_encode(array("Mensaje: "=> "Error al eliminar usuario"));
    };
};

function actualizarConsulta($conexion, $id){
    $dato = json_decode(file_get_contents("php://input"), true);
    $nombre = $dato['nombre']; 


    $sql = "UPDATE usuarios SET nombre = '$nombre' WHERE id = '$id'";
    $resultado = $conexion->query($sql); 

    if($resultado){
        echo json_encode(array("Mensaje: "=> "Nombre cambiado: $nombre"));
    }else{
        echo json_encode(array("Mensaje: " => "Error al actualizar"));
    }
    
}

?>