<?php
// buscar_producto_por_nombre.php
include "../model/conexion_bd.php";

// Obtener el valor de búsqueda del cuerpo de la solicitud POST
$valorBusqueda = $_POST['valorBusqueda'];

// Validar y sanitizar el valor de búsqueda (ejemplo básico)
$valorBusqueda = mysqli_real_escape_string($conexion, $valorBusqueda);

// Consultar la base de datos para buscar productos por nombre o código de barras
$sql = "SELECT productos.*, marcas.Nombre_Marca, categorias.Nombre_Categoria
        FROM productos
        INNER JOIN marcas ON productos.ID_Marca = marcas.ID_Marca
        INNER JOIN categorias ON productos.ID_Categoria = categorias.ID_Categoria
        WHERE Nombre LIKE '%$valorBusqueda%' OR codigo = '$valorBusqueda'";

$resultado = $conexion->query($sql);

// Manejo de errores
if (!$resultado) {
    $response = array("success" => false, "error" => $conexion->error);
} else {
    $productos = array();

    while ($producto = $resultado->fetch_assoc()) {
        $productos[] = $producto;
    }

    if (count($productos) > 0) {
        // Productos encontrados
        $response = array("success" => true, "productos" => $productos);
    } else {
        // Productos no encontrados
        $response = array("success" => false);
    }
}

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
