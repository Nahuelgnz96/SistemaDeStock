document.querySelector('#eliminarSeleccionadosLink').addEventListener('click', function (event) {
    const checkboxes = document.querySelectorAll('.form-check-input');

    event.preventDefault();

    const selectedProducts = Array.from(checkboxes)
        .filter(checkbox => checkbox.checked)
        .map(checkbox => checkbox.value);

    if (selectedProducts.length > 0) {
        // Mostrar confirmación con SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará los productos seleccionados.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                // El usuario confirmó, procede con la eliminación
                const queryString = 'categoriasAEliminar=' + selectedProducts.join(',');

                const xhr = new XMLHttpRequest();

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        if (xhr.responseText === "success") {
                            console.log('Productos eliminados correctamente.');
                            window.location.reload();
                            // Puedes agregar lógica adicional aquí si es necesario
                        } else {
                            Swal.fire('Error', "No puedes eliminar porque estás usando esta marca", 'error');
                            console.error('Error al eliminar productos:', xhr.responseText);
                        }
                    } else {
                        Swal.fire('Error', 'Error al eliminar productos', 'error');
                        console.error('Error al eliminar productos:', xhr.statusText);
                    }
                };

                xhr.open('POST', '../controllers/c_eliminar_categoria.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(queryString);
            }
        });
    } else {
        Swal.fire({
            title: 'No se han seleccionado categoria para eliminar.',
        })
    }
});
