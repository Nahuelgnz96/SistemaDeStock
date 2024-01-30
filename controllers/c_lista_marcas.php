<?php
include "../model/conexion_bd.php"; 

$sql = "SELECT * FROM marcas WHERE ID_Marca > 0 ORDER BY Nombre_Marca"; 
 
$resultado = $conexion->query($sql); 
 
// Mostrar el contenedor solo si hay registros 
if ($resultado->num_rows > 0) {
    // Imprimir el formulario que rodea la tabla
    echo "<form method='post' action='tu_pagina_de_edicion.php'>";
    // Iterar sobre los registros 
    while ($fila = $resultado->fetch_assoc()) { 
        $id = $fila["ID_Marca"];
        $nombre = $fila["Nombre_Marca"];

        // Mostrar los datos o realizar cualquier otra operación 
        echo "<tr>";
        echo "<th scope='row'>";
        echo "<input class='form-check-input' type='checkbox' value='$id' id='$id' name='marcas_seleccionadas[]'>";
        echo "</th>";
        echo "<td>".$nombre."</td>";
        echo "</tr>";
    } 
    // Agregar un botón de submit para enviar el formulario
    echo "</form>";
} 

$conexion->close(); 
?>
