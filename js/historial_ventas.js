document.addEventListener("DOMContentLoaded", () => {
    const btnBuscar = document.getElementById("btnBuscar");

    btnBuscar.addEventListener("click", () => buscarVentasPorMes());

    function buscarVentasPorMes() {
        const mesSeleccionado = document.getElementById("mes").value;
        const xhr = new XMLHttpRequest();

        xhr.open('POST', '../controllers/buscar_ventas_por_mes.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                try {
                    if (xhr.status == 200) {
                        const respuesta = JSON.parse(xhr.responseText);

                        if (respuesta.success) {
                            mostrarDetallesVenta(respuesta.ventas);
                        } else {
                            console.error('Error al obtener las ventas');
                        }
                    } else {
                        console.error('Error en la solicitud. Código de estado:', xhr.status);
                    }
                } catch (error) {
                    console.error('Error al parsear la respuesta JSON:', error);
                }
            }
        };

        xhr.send('mes=' + mesSeleccionado);
    }

    function mostrarDetallesVenta(ventas) {
        const detallesVenta = document.getElementById("detallesVenta");
        detallesVenta.innerHTML = "";

        if (ventas.length > 0) {
            const ul = document.createElement("ul");
            ul.className = "list-group";

            ventas.forEach(venta => {
                const li = document.createElement("li");
                li.className = "list-group-item pb-5";
                const fecha = new Date(venta.Fecha);
                const fechaFormateada = `${fecha.getDate()}/${fecha.getMonth() + 1}/${fecha.getFullYear()} ${fecha.getHours()}:${fecha.getMinutes()}:${fecha.getSeconds()}`;
                li.textContent = `ID Venta: ${venta.ID_Venta}, Fecha: ${fechaFormateada}, Total: $${venta.Total}`;
                li.addEventListener("click", () => mostrarDetallesAlerta(venta));

                ul.appendChild(li);
            });

            detallesVenta.appendChild(ul);
        } else {
            detallesVenta.textContent = "No hay ventas para el mes seleccionado.";
        }
    }

    function mostrarDetallesAlerta(venta) {
        const fecha = new Date(venta.Fecha);
        const fechaFormateada = `${fecha.getDate()}/${fecha.getMonth() + 1}/${fecha.getFullYear()} ${fecha.getHours()}:${fecha.getMinutes()}:${fecha.getSeconds()}`;
    
        if (venta && venta.productos && Array.isArray(venta.productos)) {
            let mensajeAlerta = `<p>(${fechaFormateada})</p>`;
    
            venta.productos.forEach(producto => {
                if (producto && producto.Producto_ID !== undefined && producto.Cantidad !== undefined && producto.Nombre !== undefined && producto.Precio !== undefined) {
                    const precioTotal = parseFloat(producto.Cantidad) * parseFloat(producto.Precio);
                    mensajeAlerta += `<p style="text-align: left;">${producto.Nombre} ${producto.MarcaNombre} ${producto.Tamaño}, $${producto.Precio} x${producto.Cantidad} <span style="float: right;">$${precioTotal.toFixed(2)}</span></p>`;
                } else {
                    console.error('Las propiedades del producto no están definidas correctamente:', producto);
                }
            });
    
            mensajeAlerta += `<p  class="text-end"><br>Total: $${venta.Total}</p>`;
    
            Swal.fire({
                title: 'Detalles de la Venta',
                html: mensajeAlerta,
                icon: 'info',
                confirmButtonText: 'OK'
            });
        } else {
            console.error('La propiedad venta o venta.productos no está definida correctamente:', venta);
        }
    }
    
});
