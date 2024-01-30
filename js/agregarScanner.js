document.addEventListener("DOMContentLoaded", () => {
    const $resultados = document.querySelector("#resultado");
    const audio = new Audio('beep.mp3');

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
        if (permitirNuevaLectura) {
            const codigoBarras = data.codeResult.code;
            $resultados.value = codigoBarras; // Actualiza el valor del input
            audio.play();

            // Evitar nuevas lecturas por un tiempo
            permitirNuevaLectura = false;
            setTimeout(() => {
                permitirNuevaLectura = true;
            }, 5000); // 5000 milisegundos = 5 segundos
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
