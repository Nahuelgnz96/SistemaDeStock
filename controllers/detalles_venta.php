<?php
// detalles_venta.php
include "../model/conexion_bd.php";

$venta_id = isset($_POST['venta_id']) ? $_POST['venta_id'] : null;

if (!is_numeric($venta_id) || $venta_id <= 0) {
    $response = array("success" => false, "error" => "ID de venta no válido");
} else {
    $sql = "SELECT dv.Producto_ID, dv.Cantidad,
                   p.Nombre AS ProductoNombre, p.Tamaño, p.Precio,
                   m.Nombre_Marca AS MarcaNombre
            FROM detalles_venta dv
            INNER JOIN productos p ON dv.Producto_ID = p.ID_Producto
            INNER JOIN marcas m ON p.ID_Marca = m.ID_Marca
            WHERE dv.ID_Venta = ?";

    // Utilizamos sentencias preparadas para prevenir inyecciones SQL
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $venta_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $productos = array();
        while ($row = $result->fetch_assoc()) {
            $producto = array(
                "Producto_ID" => $row['Producto_ID'],
                "Cantidad" => $row['Cantidad'],
                "Nombre" => $row['ProductoNombre'],
                "Tamaño" => $row['Tamaño'],
                "Precio" => $row['Precio'],
                "MarcaNombre" => $row['MarcaNombre']
            );
            $productos[] = $producto;
        }
        $response = array("success" => true, "productos" => $productos);
    } else {
        $response = array("success" => false, "error" => $conexion->error);
    }

    // Cerramos la sentencia preparada
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
