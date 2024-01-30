<?php
/* c_lista.php */
include "model/conexion_bd.php"; 

$sql = "SELECT * FROM productos ORDER BY Nombre"; 
 
$resultado = $conexion->query($sql); 
// Mostrar el contenedor solo si hay registros 
if ($resultado->num_rows > 0) {
    
    // Iterar sobre los registros 
    while ($fila = $resultado->fetch_assoc()) { 
        $id = $fila["ID_Producto"];
        $codigo = $fila["codigo"];
        $nombre = $fila["Nombre"]; 
        $categoria_id = $fila["ID_Categoria"];
        $marca_id = $fila["ID_Marca"]; 
        $tamaño = $fila["Tamaño"]; 
        $precio = $fila["Precio"];
        $stock = $fila["Stock"];
        $precio_costo = $fila["Precio_Costo"];

        // Consulta para obtener el nombre de la categoría
        $sqlCategoria = "SELECT Nombre_Categoria FROM Categorias WHERE ID_Categoria = $categoria_id";
        $resultadoCategoria = $conexion->query($sqlCategoria);
        $filaCategoria = $resultadoCategoria->fetch_assoc();
        $categoria_nombre = $filaCategoria["Nombre_Categoria"];

        // Consulta para obtener el nombre de la marca
        $sqlMarca = "SELECT Nombre_Marca FROM Marcas WHERE ID_Marca = $marca_id";
        $resultadoMarca = $conexion->query($sqlMarca);
        $filaMarca = $resultadoMarca->fetch_assoc();
        $marca_nombre = $filaMarca["Nombre_Marca"];

        // Mostrar los datos o realizar cualquier otra operación 
        echo "<tr>";
        echo "<th scope='row'>";
        echo "    <input class='form-check-input producto-checkbox' type='checkbox' data-marca='$marca_id' value='$id' id='flexCheckDefault'>";
        echo "</th>";
        echo "<td>".$codigo."</td>";
        echo "<td>".$nombre."</td>";
        echo "<td>".$marca_nombre."</td>";
        echo "<td>".$categoria_nombre."</td>";
        echo "<td>".$tamaño."</td>";
        echo "<td>$".$precio_costo."</td>";
        echo "<td>$".$precio."</td>";
        echo "<td>".$stock."</td>";
        echo "</tr>";
    }
} 

$conexion->close(); 
?>
