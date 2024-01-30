<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender Productos</title>
    <link rel="stylesheet" href="../css/style.css?v=1.3">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body data-bs-theme="dark">
    <nav class="navbar navbar-expand-lg bg-body-tertiary fixed-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="ventas.php">Historial de Ventas</a>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 nav-underline">
                <li class="nav-item">
                    <!-- Cambia el botón en el HTML a esto: -->
                    <button class="nav-link" name="btnagregar" id="btnagregar" >Guardar</button>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../">Cancelar</a>
                </li>
            </ul>
        </div>
    </nav>

    <h1 class="text-center mt-3 mb-5">Vender Productos</h1>
    <div class="container d-flex justify-content-center">
        <div id="contenedor"></div>
    </div>
    <div class="container d-flex justify-content-center">
        <input type="text" id="inputBusqueda" class="form-control bg-dark" placeholder="Buscar por nombre o código de barras">
        <!-- Cambia el botón en el HTML a esto: -->
        <button class="btn btn-primary" style="display: none" id="btnBuscar">Buscar</button>
    </div>
    <div class="container mb-5 pb-5 mt-5">
        <table class="table text-center"></table>

        <div id="detallesVenta">
            <h2 class="text-center">Detalles de la Venta</h2>
            <ul id="listaDetalles" class="list-group"></ul>
            <div id="totalVenta" class="text-end mt-3 h2"></div>
        </div>
    </div>
    <!-- Otros enlaces de estilo y metaetiquetas -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/quagga@0.12.1/dist/quagga.min.js"></script>
    <script src="../js/vender.js?v=1.9"></script>
</body>
</html>
