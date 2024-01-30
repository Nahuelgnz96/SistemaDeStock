<?php
// verificar_stock.php
include "../model/conexion_bd.php";

// Obtener el código de barras del cuerpo de la solicitud POST
$codigoBarras = $_POST['codigoBarras'];

// Consultar la base de datos para obtener el stock del producto
$sqlStock = "SELECT Stock FROM productos WHERE codigo = '$codigoBarras'";
$resultadoStock = $conexion->query($sqlStock);

if ($resultadoStock->num_rows > 0) {
    $stock = $resultadoStock->fetch_assoc()['Stock'];
    $response = array("success" => true, "stock" => $stock);
} else {
    $response = array("success" => false);
}

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
