<?php
// Establecer la conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "stock");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Configurar el juego de caracteres a UTF-8
$conexion->set_charset("utf8");

// Obtener el código de barras del cuerpo de la solicitud POST
$json = file_get_contents('php://input');
$data = json_decode($json);
$codigoBarras = $data->codigoBarras;

// Preparar la consulta SQL para insertar el código de barras en la tabla "codigos"
$sql = "INSERT INTO codigos (codigo) VALUES (?)";
$stmt = $conexion->prepare($sql);

// Vincular el parámetro e insertar el código de barras
$stmt->bind_param("s", $codigoBarras);
$resultado = $stmt->execute();

// Verificar el resultado de la inserción
if ($resultado) {
    $respuesta = array('mensaje' => 'Código de barras almacenado correctamente');
} else {
    $respuesta = array('mensaje' => 'Error al almacenar el código de barras: ' . $stmt->error);
}

// Cerrar la declaración y la conexión
$stmt->close();
$conexion->close();

// Enviar la respuesta en formato JSON al cliente JavaScript
echo json_encode($respuesta);
?>
