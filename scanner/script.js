document.addEventListener("DOMContentLoaded", () => {
    const $resultados = document.querySelector("#resultado");
    // Obtén el elemento input por su ID
    const $inputResultado = document.getElementById('resultado2');

   
    const audio = new Audio('beep.mp3');

    let codigoLeido = null;
    let permitirNuevaLectura = true;

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
            $resultados.textContent = codigoBarras;
             // Cambia el valor del input
            $inputResultado.value = codigoBarras;
            audio.play();
            
            permitirNuevaLectura = false;

            // Enviar el resultado al servidor después de un retraso de 5 segundos
            enviarResultadoAlServidorDespuesDeDelay(codigoBarras, 2000); // 5000 milisegundos = 5 segundos
        }
    });

    function enviarResultadoAlServidorDespuesDeDelay(codigoBarras, delay) {
        setTimeout(() => {
            enviarResultadoAlServidor(codigoBarras);
            permitirNuevaLectura = true; // Permitir una nueva lectura después del delay
        }, delay);
    }

    function enviarResultadoAlServidor(codigoBarras) {
        fetch('codigo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ codigoBarras: codigoBarras }),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
                // Puedes realizar acciones adicionales con la respuesta del servidor si es necesario
            })
            .catch(error => {
                console.error('Error al enviar datos al servidor:', error);
            });
    }

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
