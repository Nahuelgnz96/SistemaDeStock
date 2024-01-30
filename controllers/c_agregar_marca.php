<?php
// c_agregar_categoria.php
include "../model/conexion_bd.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar los datos JSON
    $datos = json_decode(file_get_contents("php://input"), true);

    // Verificar si la decodificación JSON fue exitosa
    if ($datos === null && json_last_error() !== JSON_ERROR_NONE) {
        $response = ['success' => false, 'message' => 'Error al decodificar los datos JSON'];
        echo json_encode($response);
        exit; // Terminar el script después de enviar la respuesta
    }

    foreach ($datos['categorias'] as $categoria) {
        $nombreCategoria = $categoria['Categoria'];

        // Utilizar una declaración preparada para mayor seguridad
        $stmt = $conexion->prepare("INSERT INTO marcas (Nombre_Marca) VALUES (?)");
        $stmt->bind_param("s", $nombreCategoria);
        $result = $stmt->execute();

        // Verificar el resultado de la inserción y manejarlo según necesites
        if ($result) {
            // Éxito
            $id_insertado = $stmt->insert_id;
            // Puedes enviar más información en la respuesta si es necesario
            $response = ['success' => true, 'message' => 'Datos guardados correctamente', 'id_insertado' => $id_insertado, 'redirect_url' => '../', 'cantidad_categorias' => count($datos['categorias'])];
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
