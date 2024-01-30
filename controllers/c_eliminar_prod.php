<?php
$conexion=new mysqli("localhost","root","","stock");
$conexion->set_charset("utf8");
if (!empty($_POST['productosAEliminar'])) {
    $productosAEliminar = array_map('intval', explode(',', $_POST['productosAEliminar']));
    
    // Validar y limpiar los IDs si es necesario

    if (!empty($productosAEliminar)) {
        $productosAEliminarStr = implode(',', $productosAEliminar);
        $sqlEliminar = "DELETE FROM productos WHERE ID_Producto IN ($productosAEliminarStr)";

        if ($conexion->query($sqlEliminar) === TRUE) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "empty";
    }
} else {
    echo "no_data";
}

$conexion->close();
?>
