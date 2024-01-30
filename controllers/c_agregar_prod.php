<?php
include "../model/conexion_bd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = json_decode(file_get_contents("php://input"), true);

    foreach ($datos['productos'] as $producto) {
        $codigo = mysqli_real_escape_string($conexion, $producto['Codigo']);
        $nombre = mysqli_real_escape_string($conexion, $producto['Nombre']);
        $categoria = (int)$producto['Categoria'];
        $marca = (int)$producto['Marca'];
        $tamaño = mysqli_real_escape_string($conexion, $producto['Tamaño']);
        $precioCosto = (float)$producto['PrecioCosto'];
        $precio = (float)$producto['Precio'];
        $stock = (int)$producto['Stock'];

        // Utilizar una declaración preparada para mayor seguridad
        $stmt = $conexion->prepare("INSERT INTO productos (codigo, Nombre, ID_Categoria, ID_Marca, Tamaño, Precio_Costo, Precio, Stock) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisddi", $codigo, $nombre, $categoria, $marca, $tamaño, $precioCosto, $precio, $stock);
        $result = $stmt->execute();

        // Verificar el resultado de la inserción y manejarlo según necesites
        if ($result) {
            // Éxito
            $id_insertado = $stmt->insert_id;
            // Puedes enviar más información en la respuesta si es necesario
            $response = ['success' => true, 'message' => 'Datos guardados correctamente', 'id_insertado' => $id_insertado, 'redirect_url' => '../', 'cantidad_productos' => count($datos['productos'])];

        } else {
            // Error
            $response = ['success' => false, 'message' => 'Error al guardar los datos: ' . $stmt->error];
        }

        $stmt->close();
    }

    // Enviar la respuesta JSON
    echo json_encode($response);
} else {
    // Si la solicitud no es POST, enviar una respuesta de error JSON
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
