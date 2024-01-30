<?php
// Incluye el archivo de conexión a la base de datos
include "../model/conexion_bd.php";
        $selectedProducts = isset($_GET['selectedProducts']) ? explode(',', $_GET['selectedProducts']) : [];
        $selectedProducts = array_map('intval', $selectedProducts); // Convertir a enteros
// Verifica si se envió el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los identificadores de los productos desde la cadena de consulta
    

    // Inicializa un array para almacenar los productos actualizados
    $productosActualizados = [];
    
    // Verifica si la clave 'cantidadProductos' está definida en $_POST y es un valor numérico
    if (isset($_POST['cantidadProductos']) && is_numeric($_POST['cantidadProductos'])) {
        $cantidadProductos = intval($_POST['cantidadProductos']);
        
        // Recorre los productos y actualiza la información
        for ($i = 1, $b = 0; $i <= $cantidadProductos; $i++, $b++) {
            echo $idProducto;
            $idProducto = $selectedProducts[$b];
            // Verifica si las claves específicas están definidas antes de acceder a ellas
            if (
                isset($_POST["Codigo$i"]) &&
                isset($_POST["Producto$i"]) &&
                isset($_POST["Categoria$i"]) &&
                isset($_POST["Marca$i"]) &&
                isset($_POST["Tamaño$i"]) &&
                isset($_POST["Precio_Costo$i"]) &&
                isset($_POST["Precio$i"]) &&
                isset($_POST["Stock$i"])
            ) {
                $producto = [
                    'codigo' => $_POST["Codigo$i"],
                    'nombre' => $_POST["Producto$i"],
                    'categoria' => $_POST["Categoria$i"],
                    'marca' => $_POST["Marca$i"],
                    'tamaño' => $_POST["Tamaño$i"],
                    'precio_Costo' => $_POST["Precio_Costo$i"],
                    'precio' => $_POST["Precio$i"],
                    'stock' => $_POST["Stock$i"],
                ];

                $productosActualizados[] = $producto;

                // Actualiza los datos en la base de datos
                $sqlActualizar = "UPDATE productos SET
                    Codigo = '{$producto['codigo']}',
                    Nombre = '{$producto['nombre']}',
                    ID_Categoria = {$producto['categoria']},
                    ID_Marca = {$producto['marca']},
                    Tamaño = '{$producto['tamaño']}',
                    Precio_Costo = {$producto['precio_Costo']},
                    Precio = {$producto['precio']},
                    Stock = {$producto['stock']}
                    WHERE ID_Producto = $idProducto";

                // Ejecuta la consulta de actualización
                $resultadoActualizar = $conexion->query($sqlActualizar);

                if ($resultadoActualizar) {
                    echo "Producto $idProducto actualizado correctamente.<br>";
                } else {
                    echo "Error al actualizar el producto $i: " . $conexion->error . "<br>";
                }
            } else {
                // Maneja el caso en el que alguna clave no está definida (puedes ignorar el producto o manejarlo según tus necesidades)
                echo "Advertencia: Alguna clave para el producto $i no está definida.<br>";
            }
        }

        // Redirecciona a index.php después de guardar
        header("Location: ../");
        exit(); // Asegura que no haya salida adicional después de la redirección
    } else {
        // Maneja el caso en el que 'cantidadProductos' no está definido o no es un valor numérico
        echo "Advertencia: La cantidad de productos no está definida o no es válida.<br>";
    }
} else {
    // Maneja el caso en el que el formulario no se envió mediante POST
   /*  echo "Error: El formulario no se ha enviado correctamente.<br>"; */
}
?>
