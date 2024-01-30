<?php
// c_eliminar_categoria.php
include "../model/conexion_bd.php";

// Verifica si se recibieron categorías a eliminar
if (isset($_POST['categoriasAEliminar'])) {
    // Obtén las categorías a eliminar desde la cadena de consulta
    $categoriasAEliminar = array_map('intval', explode(',', $_POST['categoriasAEliminar']));

    // Construye la consulta para eliminar las categorías
    $categoriasAEliminarStr = implode(',', $categoriasAEliminar);
    // Verifica si la cadena no está vacía antes de ejecutar la consulta
    if (!empty($categoriasAEliminarStr)) {
        $sqlEliminar = "DELETE FROM marcas WHERE ID_Marca IN ($categoriasAEliminarStr)";

        // Ejecuta la consulta
        if ($conexion->query($sqlEliminar) === TRUE) {
            // La eliminación fue exitosa
            echo "success";
        } else {
            // Hubo un error en la eliminación
            echo "Error al eliminar Marcas: " . $conexion->error;
        }
    } else {
        // La cadena de categorías a eliminar está vacía
        echo "Advertencia: No se proporcionaron Marcas válidas para eliminar.";
    }
} else {
    // No se recibió la variable 'categoriasAEliminar' en la solicitud POST
    echo "Advertencia: No se proporcionaron Marcas para eliminar.";
}

// Cierra la conexión a la base de datos
$conexion->close();
?>
