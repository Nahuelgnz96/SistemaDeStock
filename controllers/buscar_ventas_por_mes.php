<?php
// buscar_ventas_por_mes.php
include "../model/conexion_bd.php";

$mes = isset($_POST['mes']) ? $_POST['mes'] : null;

if (!is_numeric($mes) || $mes < 1 || $mes > 12) {
    $response = array("success" => false, "error" => "Mes no válido");
} else {
    $sql = "SELECT v.ID_Venta, v.Fecha, v.Total, dv.Producto_ID, dv.Cantidad,
                   p.Nombre AS ProductoNombre, p.Tamaño, p.Precio,
                   c.Nombre_Categoria AS CategoriaNombre,
                   m.Nombre_Marca AS MarcaNombre
            FROM ventas v
            INNER JOIN detalles_venta dv ON v.ID_Venta = dv.ID_Venta
            INNER JOIN productos p ON dv.Producto_ID = p.ID_Producto
            INNER JOIN categorias c ON p.ID_Categoria = c.ID_Categoria
            INNER JOIN marcas m ON p.ID_Marca = m.ID_Marca
            WHERE MONTH(v.Fecha) = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $mes);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $ventas = array();
        while ($row = $result->fetch_assoc()) {
            $venta_id = $row['ID_Venta'];

            // Verificamos si ya existe una entrada para esta venta
            if (!isset($ventas[$venta_id])) {
                // Si no existe, la creamos
                $ventas[$venta_id] = array(
                    "ID_Venta" => $venta_id,
                    "Fecha" => $row['Fecha'],
                    "Total" => $row['Total'],
                    "productos" => array()
                );
            }

            // Agregamos el producto a la venta correspondiente
            $ventas[$venta_id]["productos"][] = array(
                "Producto_ID" => $row['Producto_ID'],
                "Cantidad" => $row['Cantidad'],
                "Nombre" => $row['ProductoNombre'],
                "Tamaño" => $row['Tamaño'],
                "Precio" => $row['Precio'],
                "CategoriaNombre" => $row['CategoriaNombre'],
                "MarcaNombre" => $row['MarcaNombre']
            );
        }

        $response = array("success" => true, "ventas" => array_values($ventas));
    } else {
        $response = array("success" => false, "error" => $conexion->error);
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
