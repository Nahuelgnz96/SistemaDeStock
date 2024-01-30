<!-- v_agregar_prod.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/scanner.css?v=1.0">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body data-bs-theme="dark">
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-bottom">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 nav-underline">
        <li class="nav-item">
          <button class="nav-link" name="btnagregar" >Guardar</button>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../">Cancelar</a>
        </li>
    </div>
  </div>
</nav>
<h1 class="text-center mt-3">Agregar Producto</h1>
<div class="container d-flex justify-content-center">

<div id="contenedor"></div>

</div>

<div class="container mb-5 pb-5 mt-5">
    <table class="table text-center">
        <thead>
            <tr>
            <th scope="col">Escanear</th>
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
        <?php include "../model/conexion_bd.php"?>
        <tbody>
            <th><button class="btn btn-primary Escanear" id="btnEscanear">Escanear</button></th>
            <th scope="col"><input type="text" class="form-control" id="resultado" name="Codigo1" placeholder="Codigo"></th>
            <th scope="col"><input type="text" class="form-control" name="Producto1" placeholder="Producto"></th>
            <th scope="col">
            <select class="form-control" name="Categoria1">
                <?php
                    // Realizar una consulta SQL para obtener las categorías
                    $sql = "SELECT * FROM Categorias";
                    $result = $conexion->query($sql);

                    // Iterar sobre los resultados y generar opciones para el select
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['ID_Categoria'] . '">' . $row['Nombre_Categoria'] . '</option>';
                    }
                ?>
            </select>
            </th>
            <th scope="col">
                <select class="form-control" name="Marca1">
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
            </th>
            <th scope="col"><input type="text" class="form-control" name="Tamaño1" placeholder="Tamaño"></th>
            <th scope="col"><input type="number" class="form-control" name="PrecioCosto1" placeholder="Precio Costo"></th>
            <th scope="col"><input type="number" class="form-control" name="Precio1" placeholder="Precio"></th>
            <th scope="col"><input type="number" class="form-control" name="Stock1" placeholder="Stock"></th>
        </tbody>
    </table>
    

    <script src="../js/agregarProd.js?v=1.5"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    

</div>
<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>



<!-- ... Tu código HTML ... -->

<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
<!-- ... Tu código HTML ... -->

<script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        var contenedor = document.getElementById("contenedor");
        // Mueve la declaración de la constante audio fuera del evento DOMContentLoaded
        const audio = new Audio('beep.mp3');
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

        document.getElementById('btnEscanear').addEventListener('click', () => {
            contenedor.style.display = "flex";
            inicializarScanner();
        });

        Quagga.onDetected((data) => {
            const codigoBarras = data.codeResult.code;

            if (permitirNuevaLectura) {
                // Cambia el valor del input
                $inputResultado.value = codigoBarras;
                audio.play();
                detenerScanner()
                contenedor.style.display = "none";
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
</script>
</body>
</html>


