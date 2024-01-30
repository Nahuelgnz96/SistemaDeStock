<?php
//c_lista_categorias.php
include "../model/conexion_bd.php"; 

$sql = "SELECT * FROM categorias WHERE ID_Categoria > 0 ORDER BY Nombre_Categoria"; 
 
$resultado = $conexion->query($sql); 
 
// Mostrar el contenedor solo si hay registros 
if ($resultado->num_rows > 0) {
    // Iterar sobre los registros 
    
    while ($fila = $resultado->fetch_assoc()) { 
        $id = $fila["ID_Categoria"];
        $nombre = $fila["Nombre_Categoria"];

        // Mostrar los datos o realizar cualquier otra operación 
        echo "<tr>";
        echo "<th scope='row'>";
        echo "<input class='form-check-input' type='checkbox' value='$id' id='$id' name='categorias_seleccionadas[]'>";
        echo "</th>";
        echo "<td>".$nombre."</td>";
        echo "</tr>";
    } 
} 

$conexion->close(); 
?>
