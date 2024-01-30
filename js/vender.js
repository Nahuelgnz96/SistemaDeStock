let venta = [];

document.addEventListener("DOMContentLoaded", () => {
    var contenedor = document.getElementById("contenedor");
    const audio = new Audio('../audio/beep.mp3');
    let permitirNuevaLectura = true;

    const bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nombre', 'marca', 'tamaño', 'categoria'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '../controllers/buscar_productos_autocompletado.php?valorBusqueda=%QUERY',
            wildcard: '%QUERY',
            filter: function (response) {
                return response.productos.map(producto => ({
                    codigoBarras: producto.codigo,
                    nombre: producto.Nombre,
                    marca: producto.Nombre_Marca,
                    tamaño: producto.Tamaño,
                    categoria: producto.Nombre_Categoria,
                    precio: parseFloat(producto.Precio) || 0, // Manejo del precio, asegurando que sea un número
                }));
            }
        }
    });

    // Inicializa Typeahead
    $('#inputBusqueda').typeahead({
        minLength: 2,
        highlight: true,
    }, {
        name: 'productos',
        display: function(item) {
            return `${item.nombre} - ${item.marca} - ${item.tamaño} - ${item.categoria}`;
        },
        source: bloodhound
    })
    .on('typeahead:select', function (e, producto) {
        // Evento disparado cuando se selecciona un producto
        agregarProductoAVenta(producto);
    });

    function agregarProductoAVenta(producto) {
        const codigoBarras = producto.codigoBarras;

        const productoExistente = venta.find(item => item.codigoBarras === codigoBarras);

        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            venta.push({
                codigoBarras: codigoBarras,
                nombre: producto.nombre,
                precio: producto.precio,
                tamaño: producto.tamaño,
                marca: producto.marca,
                categoria: producto.categoria,
                cantidad: 1
            });
        }

        actualizarDetallesVenta(venta);
        // Limpiar el input después de agregar el producto a la venta
        $('#inputBusqueda').typeahead('val', '');
    }

    const btnGuardar = document.getElementById("btnagregar");
    btnGuardar.addEventListener("click", () => {
        guardarVenta(venta);
    });

    const btnBuscar = document.getElementById("btnBuscar");
    btnBuscar.addEventListener("click", buscarPorNombre);

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

    Quagga.onDetected((data) => {
        const codigoBarras = data.codeResult.code;
        if (permitirNuevaLectura) {
            audio.play();
            console.log('Código de barras detectado:', codigoBarras);
            console.log('Tipo de código de barras:', typeof codigoBarras);
            permitirNuevaLectura = false;

            buscarProductoEnBaseDeDatos(codigoBarras, venta);

            setTimeout(() => {
                permitirNuevaLectura = true;
            }, 1500);
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

function buscarPorNombre() {
    const inputBusqueda = document.getElementById("inputBusqueda");
    const valorBusqueda = inputBusqueda.value.trim();

    if (valorBusqueda !== "") {
        buscarProductoPorNombre(valorBusqueda, venta);
    }
}

function buscarProductoPorNombre(valorBusqueda, venta) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../controllers/buscar_producto_por_nombre.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Respuesta del servidor:', xhr.responseText);
            const respuesta = JSON.parse(xhr.responseText);
            if (respuesta.success && respuesta.productos && respuesta.productos.length > 0) {
                const producto = respuesta.productos[0]; // Accede al primer producto

                // Asegúrate de que el producto tenga la propiedad codigo
                const codigoBarras = producto.codigo;
                if (codigoBarras) {
                    const productoExistente = venta.find(item => item.codigoBarras === codigoBarras);

                    if (productoExistente) {
                        productoExistente.cantidad += 1;
                    } else {
                        venta.push({
                            codigoBarras: codigoBarras,
                            nombre: producto.Nombre,
                            precio: producto.Precio,
                            tamaño: producto.Tamaño,
                            marca: producto.Nombre_Marca,
                            categoria: producto.Nombre_Categoria,
                            cantidad: 1
                        });
                    }

                    actualizarDetallesVenta(venta);
                } else {
                    alert('El producto no tiene un código de barras definido.');
                }
            } else {
                alert('Producto no encontrado en la base de datos. Debes cargarlo antes de venderlo.');
            }
        }
    };

    xhr.send('valorBusqueda=' + valorBusqueda);
}

function buscarProductoEnBaseDeDatos(codigoBarras, venta) {
        const esCodigoBarras = !isNaN(codigoBarras);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../controllers/buscar_producto.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const respuesta = JSON.parse(xhr.responseText);
                if (respuesta.success) {
                    const producto = respuesta.producto;

                    const precio = parseFloat(producto.Precio) || 0; // Manejo del precio

                    const productoExistente = venta.find(item => item.codigoBarras === codigoBarras);

                    if (productoExistente) {
                        productoExistente.cantidad += 1;
                    } else {
                        venta.push({
                            codigoBarras: codigoBarras,
                            nombre: producto.Nombre,
                            precio: precio,
                            tamaño: producto.Tamaño,
                            marca: producto.Nombre_Marca,
                            categoria: producto.Nombre_Categoria,
                            cantidad: 1
                        });
                    }

                    actualizarDetallesVenta(venta);
                } else {
                    alert('Producto no encontrado en la base de datos. Debes cargarlo antes de venderlo.');
                }
            }
        };

        xhr.send(`${esCodigoBarras ? 'codigoBarras' : 'nombre'}=${codigoBarras}`);
    }

function guardarVenta(venta) {
    if (venta.length === 0) {
        alert('No hay productos en la venta. Debes agregar al menos uno antes de guardar.');
        return;
    }

    const xhrGuardar = new XMLHttpRequest();
    xhrGuardar.open('POST', '../controllers/c_guardar_venta.php', true);
    xhrGuardar.setRequestHeader('Content-type', 'application/json');
    xhrGuardar.onreadystatechange = function () {
        if (xhrGuardar.readyState == 4 && xhrGuardar.status == 200) {
            const respuestaGuardar = JSON.parse(xhrGuardar.responseText);
            if (respuestaGuardar.success) {
                verificarStock(venta);
            } else {
                alert('Error al guardar la venta');
            }
        }
    };

    xhrGuardar.send(JSON.stringify({ venta: venta }));
}

function verificarStock(venta) {
    let stockMenorA5 = false;
    let contadorRespuestas = 0; // Contador para manejar respuestas asincrónicas

    venta.forEach(producto => {
        const codigoBarras = producto.codigoBarras;
        const xhrStock = new XMLHttpRequest();
        xhrStock.open('POST', '../controllers/verificar_stock.php', true);
        xhrStock.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhrStock.onreadystatechange = function () {
            if (xhrStock.readyState == 4 && xhrStock.status == 200) {
                contadorRespuestas++;

                const respuestaStock = JSON.parse(xhrStock.responseText);
                if (respuestaStock.success) {
                    const stockActual = respuestaStock.stock;
                    if (stockActual < 5) {
                        stockMenorA5 = true;
                    }
                } else {
                    console.error('Error al verificar el stock.');
                }

                // Verificar si todas las respuestas han llegado
                if (contadorRespuestas === venta.length) {
                    // Después de verificar todo el stock, mostrar la alerta si es necesario
                    if (stockMenorA5) {
                        const stockActual = respuestaStock.stock;
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Alerta!',
                            text: `El stock de ${producto.nombre} es menor a 5 (${stockActual} unidades)`,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Venta realizada correctamente',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    }
                }
            }
        };
        xhrStock.send('codigoBarras=' + codigoBarras);
    });
}

function actualizarDetallesVenta(venta) {
    const listaDetalles = document.getElementById("listaDetalles");
    const totalVenta = document.getElementById("totalVenta");

    listaDetalles.innerHTML = "";

    const total = venta.reduce((sum, producto, index) => {
        if (
            typeof producto.precio === 'number' &&
            !isNaN(parseFloat(producto.precio)) &&
            typeof producto.cantidad === 'number'
        ) {
            const precioNumerico = parseFloat(producto.precio);

            const li = document.createElement("li");
            li.className = "list-group-item d-flex justify-content-between align-items-center";

            const contenedorBotonNombre = document.createElement("div");

            const btnEliminarCantidad = document.createElement("button");
            btnEliminarCantidad.className = "btn btn-danger btn-sm me-2";
            btnEliminarCantidad.textContent = "X";
            btnEliminarCantidad.addEventListener("click", () => {
                reducirCantidad(index);
            });
            contenedorBotonNombre.appendChild(btnEliminarCantidad);

            const nombreCantidadProducto = document.createElement("span");
            nombreCantidadProducto.className = "h3";
            nombreCantidadProducto.textContent = `${producto.nombre} ${producto.marca} ${producto.tamaño}`;
            contenedorBotonNombre.appendChild(nombreCantidadProducto);
            li.appendChild(contenedorBotonNombre);

            const cantidadProducto = document.createElement("span");
            cantidadProducto.className = "h3";
            cantidadProducto.textContent = `Cantidad: ${producto.cantidad}`;
            li.appendChild(cantidadProducto);

            const precioProducto = document.createElement("span");
            precioProducto.className = "h3";
            precioProducto.textContent = `$${precioNumerico.toFixed(2)}`;
            li.appendChild(precioProducto);

            listaDetalles.appendChild(li);
            sum += precioNumerico * producto.cantidad;
        } else {
            console.error("Error en los datos del producto:", producto);
        }
        return sum;
    }, 0);

    totalVenta.textContent = `Total: $${total.toFixed(2)}`;

    function reducirCantidad(index) {
        if (venta[index].cantidad > 1) {
            venta[index].cantidad -= 1;
        } else {
            venta.splice(index, 1);
        }
        actualizarDetallesVenta(venta);
    }
}
