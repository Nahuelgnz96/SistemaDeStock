// agregarCat.js

document.getElementsByName('btnagregar')[0].addEventListener('click', function () {
    // Obtener los valores de las filas completas
    var categorias = obtenerDatosFilasCompletas();

    // Verificar si hay categorías para agregar
    if (categorias.length > 0) {
        // Enviar los datos al controlador PHP
        fetch('../controllers/c_agregar_marca.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ categorias: categorias }),
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
            if (data.success) {
                // Redirigir a la URL especificada

            
                Swal.fire({
                    title: 'Marcas agregadas: ' + data.cantidad_categorias,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {
                    if (result.isConfirmed) {
                window.location.href = data.redirect_url;
                        
                    }
                });
            } else {
                // Manejar el caso de respuesta de error
                alert('Error al agregar categorías: ' + data.message);
            }
        })
        .catch(error => {
            // Si la respuesta no es JSON válido, imprimir el contenido de la respuesta
            console.error('Error:', error);
            error.text().then(errorMessage => console.error('Response content:', errorMessage));
        });
    } else {
        // Informar al usuario que no hay categorías para agregar
        alert('No hay categorías para agregar.');
    }
});

// Función para obtener los datos de las filas completas
function obtenerDatosFilasCompletas() {
    var categorias = [];

    // Iterar sobre las filas y obtener los datos de las filas completas
    for (var i = 1; i <= 5; i++) {
        var categoria = {
            Categoria: document.getElementsByName('Marca' + i)[0].value.trim()
        };

        // Verificar si la fila está completa antes de agregarla
        if (categoria.Categoria) {
            categorias.push(categoria);
        }
    }

    return categorias;
}
