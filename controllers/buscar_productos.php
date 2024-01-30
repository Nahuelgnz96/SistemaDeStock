<?php
// Incluye tu lógica de conexión a la base de datos y otras configuraciones necesarias
$conexion=new mysqli("localhost","root","","stock");
    $conexion->set_charset("utf8");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["terminoBusqueda"])) {
    // Obtén el término de búsqueda desde la URL
    $terminoBusqueda = $_GET["terminoBusqueda"];
    // Realiza la consulta para buscar productos por nombre, marca o categoría
    $sql = "SELECT * FROM productos 
            WHERE Nombre LIKE '%$terminoBusqueda%' 
               OR ID_Marca IN (SELECT ID_Marca FROM marcas WHERE Nombre_Marca LIKE '%$terminoBusqueda%') 
               OR ID_Categoria IN (SELECT ID_Categoria FROM categorias WHERE Nombre_Categoria LIKE '%$terminoBusqueda%')";

    $resultado = $conexion->query($sql);

    // Mostrar los resultados
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            // Mostrar los resultados como desees
        $id = $fila["ID_Producto"];
        $nombre = $fila["Nombre"]; 
        $categoria_id = $fila["ID_Categoria"];
        $marca_id = $fila["ID_Marca"]; 
        $tamaño = $fila["Tamaño"]; 
        $precio = $fila["Precio"];
        $stock = $fila["Stock"];

        // Consulta para obtener el nombre de la categoría
        $sqlCategoria = "SELECT Nombre_Categoria FROM categorias WHERE ID_Categoria = $categoria_id";
        $resultadoCategoria = $conexion->query($sqlCategoria);
        $filaCategoria = $resultadoCategoria->fetch_assoc();
        $categoria_nombre = $filaCategoria["Nombre_Categoria"];

        // Consulta para obtener el nombre de la marca
        $sqlMarca = "SELECT Nombre_Marca FROM marcas WHERE ID_Marca = $marca_id";
        $resultadoMarca = $conexion->query($sqlMarca);
        $filaMarca = $resultadoMarca->fetch_assoc();
        $marca_nombre = $filaMarca["Nombre_Marca"];
            echo "<tr>";
        echo "<th scope='row'>";
        echo "    <input class='form-check-input producto-checkbox' type='checkbox' data-marca='$marca_id' value='$id' id='flexCheckDefault'>";
        echo "</th>";
        echo "<td>".$nombre."</td>";
        echo "<td>".$marca_nombre."</td>";
        echo "<td>".$categoria_nombre."</td>";
        echo "<td>".$tamaño."</td>";
        echo "<td>".$precio."</td>";
        echo "<td>".$stock."</td>";
        echo "</tr>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}
?>
