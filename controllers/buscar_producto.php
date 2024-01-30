<?php
// buscar_producto.php
include "../model/conexion_bd.php";

// Obtener el código de barras del cuerpo de la solicitud POST
$codigoBarras = $_POST['codigoBarras'];

// Consultar la base de datos para buscar el producto por código de barras
$sql = "SELECT productos.*, marcas.Nombre_Marca, categorias.Nombre_Categoria
        FROM productos
        INNER JOIN marcas ON productos.ID_Marca = marcas.ID_Marca
        INNER JOIN categorias ON productos.ID_Categoria = categorias.ID_Categoria
        WHERE codigo = '$codigoBarras'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    // Producto encontrado
    $producto = $resultado->fetch_assoc();
    $response = array("success" => true, "producto" => $producto);
    
} else {
    // Producto no encontrado
    $response = array("success" => false);
}

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
