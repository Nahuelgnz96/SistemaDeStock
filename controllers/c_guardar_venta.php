<?php
// guardar_venta.php
include "../model/conexion_bd.php";

// Obtener la información de la venta desde la solicitud POST
$data = json_decode(file_get_contents("php://input"));

// Verificar que la información es válida
if ($data && isset($data->venta) && is_array($data->venta)) {
    // Iniciar una transacción para asegurar la consistencia de la base de datos
    $conexion->begin_transaction();

    try {
        // Calcular el total de la venta
        $totalVenta = 0;
        foreach ($data->venta as $producto) {
            $totalVenta += $producto->cantidad * $producto->precio;
        }
        // Establecer la zona horaria a Buenos Aires
        date_default_timezone_set('America/Argentina/Buenos_Aires');

        // Obtener la fecha y hora actual en Buenos Aires
        $fechaHoraBuenosAires = new DateTime();
        $fechaHoraBuenosAires->setTimezone(new DateTimeZone('America/Argentina/Buenos_Aires'));

        // Formatear la fecha y hora según tus necesidades
        $fechaFormateada = $fechaHoraBuenosAires->format('Y-m-d H:i:s');

        // Insertar la venta en la tabla 'ventas'
        $fechaVenta = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
        $sqlVenta = "INSERT INTO ventas (Fecha, Total) VALUES ('$fechaFormateada', $totalVenta)";
        $conexion->query($sqlVenta);
        
        // Obtener el ID de la venta recién insertada
        $idVenta = $conexion->insert_id;

        // Recorrer la lista de productos de la venta e insertarlos en la tabla 'detalle_venta'
        foreach ($data->venta as $producto) {
            $codigoBarras = $producto->codigoBarras;
            $cantidad = $producto->cantidad;
            $precio = $producto->precio;

            // Obtener el ID_Producto correspondiente al código de barras
            $sqlGetIdProducto = "SELECT ID_Producto FROM productos WHERE codigo = '$codigoBarras'";
            $resultIdProducto = $conexion->query($sqlGetIdProducto);
            
            if ($resultIdProducto->num_rows > 0) {
                $row = $resultIdProducto->fetch_assoc();
                $idProducto = $row['ID_Producto'];

                // Insertar en la tabla 'detalle_venta'
                $sqlDetalleVenta = "INSERT INTO detalles_venta (ID_Venta, Producto_ID, Cantidad, Precio) 
                                    VALUES ($idVenta, $idProducto, $cantidad, $precio)";
                $conexion->query($sqlDetalleVenta);

                // Actualizar el stock del producto en la tabla 'productos'
                $sqlActualizarStock = "UPDATE productos SET Stock = Stock - $cantidad WHERE ID_Producto = $idProducto";
                $conexion->query($sqlActualizarStock);
            } else {
                // Enviar una respuesta indicando que ha ocurrido un error al procesar la venta
                $response = array("success" => false, "error" => "Producto no encontrado: $codigoBarras");
                $conexion->rollback();
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
        }

        // Confirmar la transacción si todo ha sido exitoso
        $conexion->commit();

        // Enviar una respuesta indicando que la venta se ha guardado correctamente
        $response = array("success" => true);
    } catch (Exception $e) {
        // Revertir la transacción si hay algún error
        $conexion->rollback();

        // Enviar una respuesta indicando que ha ocurrido un error al procesar la venta
        $response = array("success" => false, "error" => $e->getMessage());
    }
} else {
    // Enviar una respuesta indicando que ha ocurrido un error al procesar la venta
    $response = array("success" => false, "error" => "Datos de venta no válidos");
}

// Enviar la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
