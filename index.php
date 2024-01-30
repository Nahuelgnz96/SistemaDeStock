<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css?v=1.2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body data-bs-theme="dark">
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-bottom p-0">
  <div class="container-fluid">
    <a class="navbar-brand text-center" href="views/ventas.php">Historial de Ventas</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0 nav-underline">
        <li class="nav-item">
          <a class="nav-link text-center" href="views/v_agregar_prod.php">Agregar Producto</a>
        </li>
        <li class="nav-item">
          <input type="submit" class="nav-link text-center" name="editarSeleccionados" value="Editar Seleccionados">
        </li>
        <li class="nav-item">
          <a id="eliminarSeleccionadosLink" class="nav-link text-center" href="#">Eliminar Seleccionados</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center" href="views/v_lista_categorias.php">Categorias</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-center" href="views/v_lista_marcas.php">Marcas</a>
        </li>
      </ul>
      <form class="d-flex" id="formBusqueda">
      <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" id="terminoBusqueda">
      <button class="btn btn-outline-success" type="submit">Buscar</button>

</form>
    </div>
  </div>
</nav>



<h1 class="text-center mt-3 mb-5">Lista de Productos</h1>
<div class="container d-flex justify-content-center">

<div id="contenedor"></div>

</div>
<div class="container">

<?php include "model/conexion_bd.php"?>
<div class="row">
<div class="col-4">
<a class="btn btn-success btn-lg" href="views/vender.php" id="vender" >Vender</a>
    <a class="btn btn-primary btn-lg" id="btnBuscarEscaner" >Buscar(Escaner)</a>
    
  </div>
    <div class="col-8">
        <div class="row">
        <div class="col-9 d-flex justify-content-end">
            <button class="btn btn-primary me-3 " id="seleccionarTodos" >Seleccionar Todos</button>
            <button class="btn btn-primary me-0" id="seleccionarProductosMarca">Seleccionar Con La Marca</button>
        </div>
        <div class="col-3">
            <select class="form-control text-center bg-primary" id="seleccionarMarca"  name="Marca5">
                <option selected>Seleccione Marca</option>
                <?php
                    // Consulta SQL para obtener las marcas
                    $sqlMarcas = "SELECT ID_Marca, Nombre_Marca FROM Marcas";
                    $resultadoMarcas = $conexion->query($sqlMarcas);

                    // Mostrar las opciones del select de marcas
                    while ($filaMarca = $resultadoMarcas->fetch_assoc()) {
                        echo "<option value='" . $filaMarca['ID_Marca'] . "'>" . $filaMarca['Nombre_Marca'] . "</option>";
                    }
                ?>
            </select>
        </div>

        </div>
        
    </div>  
</div>

                
<form id="editarFormulario" class="mb-5 pb-5" method="post" action="controllers/c_editar_prod.php">
    <table id="tabla1" class="table text-center">
    
        <thead>
            <tr>
                <th scope="col">Seleccionar</th>
                <th scope="col">Codigo</th>
                <th scope="col">Producto</th>
                <th scope="col">Marca</th>
                <th scope="col">Categoria</th>
                <th scope="col">Tamaño</th>
                <th scope="col">Precio Costo</th>
                <th scope="col">Precio</th>
                <th scope="col">Stock</th>
            </tr>
        </thead>
        <tbody>
        <div id="resultadosBusqueda" class="mt-3"></div>
            <?php include "controllers/c_lista.php"; ?>
            
        </tbody>
        
    </table>
    <!-- Agrega un botón para enviar los datos seleccionados -->
    
    <input type="hidden" name="selectedProducts" id="selectedProducts">
</form>

</div>
<div id="qr-reader" style="width: 600px"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script>

document.addEventListener("DOMContentLoaded", () => {
        var contenedor = document.getElementById("contenedor");
        // Mueve la declaración de la constante audio fuera del evento DOMContentLoaded
        const audio = new Audio('audio/beep.mp3');
        let $inputResultado;
        let permitirNuevaLectura = true;

        function inicializarScanner() {
            Quagga.init({
                inputStream: {
                    constraints: {
                        width: 1920,
                        height: 1080,
                    },
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#contenedor'),
                },
                decoder: {
                    readers: ["ean_reader"]
                }
            }, function (err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("Iniciado correctamente");
                Quagga.start();
            });
        }

        // Obtén el elemento input por su ID después de que el DOM se haya cargado
        $inputResultado = document.getElementById('resultado');
        function detenerScanner() {
            Quagga.stop();
        }

        document.getElementById('btnBuscarEscaner').addEventListener('click', () => {
            contenedor.style.display = "flex";
            inicializarScanner();
        });

        Quagga.onDetected((data) => {
            const codigoBarras = data.codeResult.code;
                function buscarProductosEscaner() {
                  // Filtra las filas según el término de búsqueda
                  const filas = document.querySelectorAll('.table tbody tr');

                  for (const fila of filas) {
                      const codigoProducto = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();

                      // Ajusta según la posición de la columna de códigos en tu tabla

                      // Verifica si el código del producto coincide con el término de búsqueda
                      if (codigoProducto.includes(codigoBarras)) {
                          // Si encuentra una coincidencia, muestra una alerta con la información del producto
                          const nombreProducto = fila.querySelector('td:nth-child(3)').textContent; // Ajusta según la posición de la columna del nombre del producto en tu tabla
                          const categoriaProducto = fila.querySelector('td:nth-child(5)').textContent; // Ajusta según la posición de la columna de categoría en tu tabla
                          const marcaProducto = fila.querySelector('td:nth-child(4)').textContent; // Ajusta según la posición de la columna de marca en tu tabla
                          const precioProducto = fila.querySelector('td:nth-child(8)').textContent;
                          const precioCostoProducto = fila.querySelector('td:nth-child(7)').textContent;
                          const tamañoProducto = fila.querySelector('td:nth-child(6)').textContent;
                          const cantidadProducto = fila.querySelector('td:nth-child(9)').textContent;
                          /* alert(`Información del Producto:\nCódigo: ${codigoProducto}\nNombre: ${nombreProducto}\nMarca: ${marcaProducto}\nTamaño: ${tamañoProducto}\nCategoría: ${categoriaProducto}\nPrecio Costo: ${precioCostoProducto}\nPrecio: ${precioProducto}`); */
                          Swal.fire({
                              title: `${nombreProducto} ${marcaProducto} ${tamañoProducto}`,
                              html: `<div></div><div>Precio Costo: ${precioCostoProducto}</div><div>Precio: ${precioProducto}</div><div>Cantidad: ${cantidadProducto}</div>`,
                          });

                          // Detén la búsqueda una vez que encuentres una coincidencia
                          return;
                      }
                  }

                    // Si no se encuentra ninguna coincidencia, muestra una alerta indicando que no se encontró el producto
                  alert('Producto no encontrado.');
                }

            if (permitirNuevaLectura) {
                // Cambia el valor del input
                audio.play();
                detenerScanner();
                console.log(codigoBarras);
                console.log('Tipo de códigoBarras:', typeof codigoBarras);
                contenedor.style.display = "none";
                buscarProductosEscaner();
            }
      });

        Quagga.onProcessed(function (result) {
            var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;

            if (result) {
                if (result.boxes) {
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function (box) {
                        return box !== result.box;
                    }).forEach(function (box) {
                        Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                    });
                }

                if (result.box) {
                    Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
                }

                if (result.codeResult && result.codeResult.code) {
                    Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
                }
            }
        });
      });

  // tu_script.js
  function buscarProductos() {
    const terminoBusqueda = document.getElementById('terminoBusqueda').value.toLowerCase();
    
    // Filtra las filas según el término de búsqueda
    const filas = document.querySelectorAll('.table tbody tr');

    filas.forEach(function (fila) {
    const nombreProducto = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
    const nombreMarca = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
    const nombreCategoria = fila.querySelector('td:nth-child(5)').textContent.toLowerCase();

    // Ajusta según la posición de la columna de nombres en tu tabla

    // Verifica si el nombre del producto, marca o categoría incluye el término de búsqueda y agrega o quita las clases 'mostrar' y 'ocultar' en consecuencia
    fila.classList.toggle('mostrar', nombreProducto.includes(terminoBusqueda) || nombreMarca.includes(terminoBusqueda) || nombreCategoria.includes(terminoBusqueda));
    fila.classList.toggle('ocultar', !nombreProducto.includes(terminoBusqueda) && !nombreMarca.includes(terminoBusqueda) && !nombreCategoria.includes(terminoBusqueda));
});
}



document.addEventListener('DOMContentLoaded', function () {
  const formBusqueda = document.getElementById('formBusqueda');

            formBusqueda.addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar que el formulario se envíe de forma predeterminada

                buscarProductos(); // Llamar a la función buscarProductos
            });
    // Obtén referencia al botón y a los checkboxes de productos
    const botonSeleccionarTodos = document.getElementById('seleccionarTodos');
    const botonSeleccionarProductosMarca = document.getElementById('seleccionarProductosMarca');
    const checkboxesProductos = document.querySelectorAll('.form-check-input');

    // Agrega un evento al botón para seleccionar todos los checkboxes
    botonSeleccionarTodos.addEventListener('click', function () {
            let todosSeleccionados = true;

            checkboxesProductos.forEach(function (checkbox) {
                if (!checkbox.checked) {
                    todosSeleccionados = false;
                }
            });

            checkboxesProductos.forEach(function (checkbox) {
                checkbox.checked = !todosSeleccionados;
            });
        });
      // Agrega un evento al botón para seleccionar productos de la marca elegida
      botonSeleccionarProductosMarca.addEventListener('click', function () {
    const marcaSeleccionada = document.getElementById('seleccionarMarca').value;

    // Verificar si todos los checkboxes de la marca seleccionada están marcados
    const todosMarcados = Array.from(checkboxesProductos)
        .filter(checkbox => checkbox.getAttribute('data-marca') === marcaSeleccionada)
        .every(checkbox => checkbox.checked);

    // Alternar entre marcar y desmarcar según la condición
    checkboxesProductos.forEach(function (checkbox) {
        checkbox.checked = !todosMarcados && checkbox.getAttribute('data-marca') === marcaSeleccionada;
    });
});
    const checkboxes = document.querySelectorAll('.form-check-input');

document.querySelector('.nav-link[name="editarSeleccionados"]').addEventListener('click', function (event) {
    event.preventDefault();
    // Obtener los IDs de las filas seleccionadas
    const selectedProducts = Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);

        if (selectedProducts.length > 0) {
    // Imprimir los IDs en la consola
    console.log('IDs seleccionados:', selectedProducts);

    // Construir la cadena de consulta
    const queryString = 'selectedProducts=' + selectedProducts.join(',');

    // Obtener la URL actual
    const currentUrl = window.location.href;

    // Redireccionar a la URL del formulario con la cadena de consulta
    window.location.href = currentUrl + 'views/v_editar_prod.php?' + queryString;
} else {
        Swal.fire({
            title: 'No se han seleccionado productos para editar.',
        })
    }



    });
});
</script>
<script src="js/eliminarProd.js?v=1.0"></script>

<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js
"></script>
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
</body>
</html>


