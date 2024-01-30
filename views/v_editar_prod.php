<!-- v_editar_prod.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body data-bs-theme="dark">
<?php include "../controllers/c_guardar_prod.php"?>
<h1 class="text-center mt-3">Editar Producto</h1>
<div class="container mb-5 pb-5 mt-5">
<form id="editarFormulario" method="post">
<label for="porcentaje">Porcentaje de cambio:</label>
    <input type="number" class="form-control" name="porcentaje" id="porcentaje" placeholder='Ingrese el porcentaje "%"' >
    <button type="button" class="btn btn-primary" onclick="sumarPorcentaje()">Aumentar Precios</button>
    <button type="button" class="btn btn-danger" onclick="resetearPrecios()">Resetear</button>
    <button type="button" class="btn btn-primary" onclick="restarPorcentaje()">Bajar Precios</button>
        <table class="table text-center">
            <thead>
                <tr>
                    <th scope="col">Codigo</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Marca</th>
                    <th scope="col">Tamaño</th>
                    <th scope="col">Precio Costo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Stock</th>
                </tr>
            </thead>
            <tbody>
            <?php
include "../controllers/c_editar_prod.php";
$preciosOriginales = array_column($productos, 'Precio');
for ($i = 1; $i <= $cantidadIDs; $i++):
    $producto = $productos[$i - 1];
?>

<input type="hidden" name="cantidadProductos" value="<?= $cantidadIDs ?>">
    <tr>
    <td><input type="text" class="form-control" name="Codigo<?= $i ?>" placeholder="Codigo" value="<?= isset($producto['codigo']) ? $producto['codigo'] : '' ?>"></td>
        <td><input type="text" class="form-control" name="Producto<?= $i ?>" placeholder="Producto" value="<?= isset($producto['Nombre']) ? $producto['Nombre'] : '' ?>"></td>
        <td>
            <select class="form-control" name="Categoria<?= $i ?>">
                <?php
                $sql = "SELECT * FROM Categorias";
                $result = $conexion->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($producto['ID_Categoria']) && $row['ID_Categoria'] == $producto['ID_Categoria']) ? 'selected' : '';
                    echo '<option value="' . $row['ID_Categoria'] . '" ' . $selected . '>' . $row['Nombre_Categoria'] . '</option>';
                }
                ?>
            </select>
        </td>
        <td>
            <select class="form-control" name="Marca<?= $i ?>">
                <?php
                $sqlMarcas = "SELECT ID_Marca, Nombre_Marca FROM Marcas";
                $resultadoMarcas = $conexion->query($sqlMarcas);

                while ($filaMarca = $resultadoMarcas->fetch_assoc()) {
                    $selected = (isset($producto['ID_Marca']) && $filaMarca['ID_Marca'] == $producto['ID_Marca']) ? 'selected' : '';
                    echo "<option value='" . $filaMarca['ID_Marca'] . "' " . $selected . ">" . $filaMarca['Nombre_Marca'] . "</option>";
                }
                ?>
            </select>
        </td>
        <td><input type="text" class="form-control" name="Tamaño<?= $i ?>" placeholder="Tamaño" value="<?= isset($producto['Tamaño']) ? $producto['Tamaño'] : '' ?>"></td>
        <td><input type="number" class="form-control" name="Precio_Costo<?= $i ?>" placeholder="Precio Costo" value="<?= isset($producto['Precio_Costo']) ? $producto['Precio_Costo'] : '' ?>"></td>
        <td><input type="number" class="form-control" name="Precio<?= $i ?>" placeholder="Precio" value="<?= isset($producto['Precio']) ? $producto['Precio'] : '' ?>"></td>
        <td><input type="number" class="form-control" name="Stock<?= $i ?>" placeholder="Stock" value="<?= isset($producto['Stock']) ? $producto['Stock'] : '' ?>"></td>
    </tr>
<?php endfor; ?>

            </tbody>
        </table>
        <button class="btn btn-primary" name="btnagregar" >Guardar</button>
        
        <a class="btn btn-danger" href="../">Cancelar</a>
        
        <input type="hidden" name="selectedProducts" id="selectedProducts">
    </form>
    

    <script>


document.addEventListener('DOMContentLoaded', function () {
    // Obtén el elemento del campo de porcentaje
    const porcentajeInput = document.getElementById('porcentaje');
    const preciosOriginales = <?php echo json_encode($preciosOriginales); ?>;
    /* // Agrega un evento al cambiar el valor del porcentaje
    porcentajeInput.addEventListener('input', function () {
        // Llama a la función que actualizará dinámicamente los precios
        
    }); */
});

// Función para actualizar dinámicamente los precios
function sumarPorcentaje() {
    // Obtén el valor del porcentaje
    const porcentaje = parseFloat(document.getElementById('porcentaje').value);

    // Verifica si el porcentaje es un número válido
    if (!isNaN(porcentaje)) {
        // Obtén todos los campos de precio en la tabla
        const camposPrecio = document.querySelectorAll('[name^="Precio"]');

        // Itera sobre los campos de precio y actualiza sus valores
        camposPrecio.forEach(function (campo) {
            const precioActual = parseFloat(campo.value);
            const nuevoPrecio = precioActual * (1 + porcentaje / 100);

            // Actualiza el contenido del campo de precio
            campo.value = nuevoPrecio.toFixed(2); // Ajusta el número de decimales
        });
    }
}
function restarPorcentaje() {
    // Obtén el valor del porcentaje
    const porcentaje = parseFloat(document.getElementById('porcentaje').value);

    // Verifica si el porcentaje es un número válido
    if (!isNaN(porcentaje)) {
        // Obtén todos los campos de precio en la tabla
        const camposPrecio = document.querySelectorAll('[name^="Precio"]');

        // Itera sobre los campos de precio y actualiza sus valores
        camposPrecio.forEach(function (campo) {
            const precioActual = parseFloat(campo.value);
            const nuevoPrecio = precioActual * (1 - porcentaje / 100);

            // Actualiza el contenido del campo de precio
            campo.value = nuevoPrecio.toFixed(2); // Ajusta el número de decimales
        });
    }
}

function resetearPrecios() {
    window.location.reload();
    }



</script>

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js
"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</div>
</body>
</html>