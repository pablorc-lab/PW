// Cada vez que el usuario vaya a enviar la información se activa el evento
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', verificarRegistro); 
});

// Esta función muestra los mensajes de error encima de los campos correspondientes
function mostrarErrores(camposErrores) {
    // Eliminar cualquier mensaje de error existente para todos los campos
    document.querySelectorAll('.error').forEach(function(error) {
        error.remove();
    });

    // Iterar sobre los campos y mensajes de error
    for (let campo in camposErrores) {
        let mensaje = camposErrores[campo];
        // Buscamos el elemento por su ID
        let campoElemento = document.getElementById(campo);

        // Crear un nuevo elemento p para el mensaje de error
        let error = document.createElement("p");
        error.classList.add("error");

        // Añadir el mensaje de error y darle una clase para identificarlo
        error.textContent = mensaje;

        // Aplicar estilos directamente al elemento p
        error.style.margin = 0;
        error.style.color = "red";
        error.style.fontWeight = "bold";

        // Insertar el mensaje de error antes del campo de entrada
        campoElemento.parentNode.insertBefore(error, campoElemento);
    }
}


// Función que verifica si cada campo del registro está escrito correctamente
function verificarRegistro(evento) {
    // Diccionario para almacenar campos y mensajes de error
    let errores = {};

    
    let titulo = document.getElementById("titulo").value;
    // Verificar si los campos están vacíos
    if (titulo.trim() === "") 
        errores["titulo"] = "Introduzca el título de la obra";
    


    let autor = document.getElementById("autor").value;
    let reg_textos = /^[a-zA-ZÀ-ÿ\u00f1\u00d1\s]+$/;
    if (!reg_textos.test(autor)) 
        errores["autor"] = "Introduzca el autor de la obra (solo letras)";
    


    let anio = document.getElementById("anio").value;
    if (anio.trim() === "") 
        errores["anio"] = "Introduzca el año de la obra";
    

    let categoria = document.getElementById("categoria").value;
    if (categoria.trim() === "") 
        errores["categoria"] = "Introduzca la categoría de la obra";
    

    let ruta_imagen = document.getElementById("ruta_imagen").value;
    if (ruta_imagen.trim() === "") 
        errores["ruta_imagen"] = "Introduzca el nombre de la imagen de la obra";
    
    // Verificar si el diccionario de campos y mensajes de errores no está vacío
    if (Object.keys(errores).length !== 0) {
        evento.preventDefault(); // Evitar el envío del formulario
        mostrarErrores(errores); // Mostrar todos los errores
    }
}
