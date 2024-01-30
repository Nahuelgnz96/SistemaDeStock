<!DOCTYPE html>
<!-- v_lista_categorias.php -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.min.css
" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body data-bs-theme="dark">
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-bottom p-0">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0 nav-underline">
        <li class="nav-item">
          <a class="nav-link" href="v_agregar_categoria.php">Agregar</a>
        </li>
        <li class="nav-item">
        <a id="eliminarSeleccionadosLink" class="nav-link" href="#">Eliminar Seleccionados</a>
      </li>
        <li class="nav-item">
          <a class="nav-link" href="../">Volver</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<h1 class="text-center mt-3 mb-5">Lista de Categorias</h1>
<div class="container">
<button class="btn btn-primary" id="seleccionarTodos" >Seleccionar Todos</button>
  <form id="eliminarCategoriasForm2" method="post" >
      <table class="table text-center p-5 mb-5">
          <thead>
              <tr>
              <th scope="col">Seleccionar</th>
              <th scope="col">Categoria</th>
              </tr>
          </thead>
          <tbody>
              <?php include "../controllers/c_lista_categorias.php"?>
          </tbody>
      </table>
      <input type="hidden" name="selectedProducts" id="selectedProducts">
  </form>
</div>
<script src="../js/eliminarCat.js?v=1.4"></script>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.2/dist/sweetalert2.all.min.js
"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const checkboxesProductos = document.querySelectorAll('.form-check-input');
  const botonSeleccionarTodos = document.getElementById('seleccionarTodos');
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
      });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>