// agregarProd.js
document.getElementsByName('btnagregar')[0].addEventListener('click', function() {
    // Obtener los valores de las filas completas
    var productos = obtenerDatosFilasCompletas();

    // Enviar los datos al controlador PHP
    fetch('../controllers/c_agregar_prod.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ productos: productos }),
    })
    .then(response => {
        // Verificar si la respuesta es un JSON válido
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Manejar la respuesta del servidor si es necesario
        console.log(data);

        // Verificar si la redirección está presente en la respuesta
        if (data.redirect_url) {
            Swal.fire({
                title: 'Producto agregados: ' + data.cantidad_productos,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
            }).then((result) => {
                if (result.isConfirmed) {
            window.location.href = data.redirect_url;
                    
                }
            });
        }
    })
    .catch(error => {
        // Si la respuesta no es JSON válido, imprimir el contenido de la respuesta
        console.error('Error:', error);
        error.text().then(errorMessage => console.error('Response content:', errorMessage));
    });
});

// Función para obtener los datos de las filas completas
function obtenerDatosFilasCompletas() {
    var productos = [];

    // Iterar sobre las filas y obtener los datos de las filas completas
    for (var i = 1; i <= 1; i++) {
        var producto = {
            Codigo: document.getElementsByName('Codigo' + i)[0].value,
            Nombre: document.getElementsByName('Producto' + i)[0].value,
            Categoria: document.getElementsByName('Categoria' + i)[0].value,
            Marca: document.getElementsByName('Marca' + i)[0].value,
            Tamaño: document.getElementsByName('Tamaño' + i)[0].value,
            PrecioCosto: document.getElementsByName('PrecioCosto' + i)[0].value,
            Precio: document.getElementsByName('Precio' + i)[0].value,
            Stock: document.getElementsByName('Stock' + i)[0].value,
        };

        // Verificar si la fila está completa antes de agregarla
        if (producto.Codigo && producto.Nombre && producto.Categoria && producto.Marca && producto.Tamaño && producto.PrecioCosto && producto.Precio && producto.Stock) {
            productos.push(producto);
        }
    }

    return productos;
}

