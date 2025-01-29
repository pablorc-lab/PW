function mostrar_ventana() {
    // Obtener la información de la página original
    var informacionPagina = `
    <h2>Información de la Página:</h2>
    <p><strong>URL:</strong> ${window.location.href}</p>
    <p><strong>Protocolo:</strong> ${window.location.protocol}</p>
    <p><strong>Nombre del Servidor:</strong> ${window.location.hostname}</p>
    <p><strong>Puerto:</strong> ${window.location.port}</p>
    `;

    // Calcular el margen para la ventana emergente
    var margen = 50; // Puedes ajustar este valor según lo que desees

    // Calcular las coordenadas para posicionar la ventana emergente
    var left = margen;
    var top = margen;

    // Crear la ventana emergente y establecer su contenido y posición
    var ventana = window.open('', 'InformacionPagina', 'width=600,height=400,left=50,top=50,scrollbars=yes');
    ventana.document.write(`
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Información de la Página y el Navegador</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
            }
            h2 {
                color: #333;
            }
            p {
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        ${informacionPagina}
    </body>
    </html>
    `);
    ventana.document.close(); // Finaliza la escritura en el documento
}

// Obtener todas las imágenes con la clase pasar-imagen
var imagenes = document.querySelectorAll('.pasar-imagen');

// Agregar el evento mouseover a cada imagen
imagenes.forEach(function(imagen) {
    imagen.addEventListener('mouseover', function() {
        

        // Llamar a la función mostrar_ventana con el título y la categoría obtenidos
        mostrar_ventana();
    });
});