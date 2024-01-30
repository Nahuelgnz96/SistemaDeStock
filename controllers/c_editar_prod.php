<?php
// c_editar_prod.php
include "../model/conexion_bd.php";
// Obtener los identificadores de los productos desde la cadena de consulta
$selectedProducts = isset($_GET['selectedProducts']) ? explode(',', $_GET['selectedProducts']) : [];
$selectedProducts = array_map('intval', $selectedProducts); // Convertir a enteros

// Contar la cantidad de IDs seleccionados
$cantidadIDs = count($selectedProducts);

$productos = [];

for ($i = 0; $i < $cantidadIDs; $i++) {
    // Obtener el ID del producto actual
    $idProducto = $selectedProducts[$i];

    // Obtener los datos del producto desde la base de datos usando el ID
    $sqlProducto = "SELECT * FROM productos WHERE ID_Producto = $idProducto";
    $resultadoProducto = $conexion->query($sqlProducto);

    if ($resultadoProducto) {
        $producto = $resultadoProducto->fetch_assoc();
        $productos[] = $producto;
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
}


?>
