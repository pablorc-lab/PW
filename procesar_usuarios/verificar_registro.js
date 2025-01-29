// Cada vez que el usuario vaya a enviar la información se activa el evento
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formularioRegistro").addEventListener('submit', verificar_registro); 
});

// A esta función se le pasa un diccionarios de campos con sus
// mensajes de error y los muestra encima de los mismos
function mostrarErrores(camposErrores) {
    // Eliminar cualquier mensaje de error existente para todos los campos
    document.querySelectorAll('.error').forEach(function(error) {
        error.remove();
    });

    // Iterar sobre los campos y mensajes de error
    for (let campo in camposErrores) {
        let mensaje = camposErrores[campo];
        // Buscamos el elemento por su ID y, si no lo encuentra, lo busca por su clase (para contenedores)
        let campoElemento = document.getElementById(campo) || document.querySelector('.' + campo);

        // Crear un nuevo elemento p para el mensaje de error
        let error = document.createElement("p");
        error.classList.add("error");

        // Añadir el mensaje de error y darle una clase para identificarlo
        error.textContent = mensaje;

        // Aplicar estilos directamente al elemento p
        error.style.margin = 0;
        error.style.color = "red"
        error.style.fontWeight = "bold";
        error.style.fontStyle = "italic"; // Texto en cursiva

        campoElemento.parentNode.insertBefore(error, campoElemento);
    }
}


// Funcion que verifica si cada registro está escrito correctamente
function verificar_registro(evento) {
    
    // Diccionario para almacenar campos y mensajes de error
    let errores = {};

    // Campos para el correo
    let correo = document.getElementById("campoCorreo");
    const reg_correo = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;

    if (!reg_correo.test(correo.value)) {
        errores["campoCorreo"] = "El correo electrónico no es válido";
    }
    

    // Campos pa la contraseña
    let contraseña = document.querySelector('.contenedor-contraseña #campoContraseña');
    let contraseña2 = document.querySelector('.contenedor-contraseña #campoContraseña2');
    const reg_contra = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]+$/;

    if(!reg_contra.test(contraseña.value) ) {
        errores["contenedor-contraseña"] = "Debse incluir al menos una letra mayúscula, minúscula y dígito";
    }

    else if (contraseña.value !== contraseña2.value || contraseña.value.length < 8) {
        errores["contenedor-contraseña"] = "Las contraseñas no coinciden o tienen menos de 8 carácteres";
    }
    

    // Campos para el NOMBRE, APELLIDO
    let reg_textos = /^[a-zA-ZÀ-ÿ\u00f1\u00d1\s]+$/;
    let nombre = document.getElementById("campoNombre").value;
    let apellidos = document.getElementById("campoApellidos").value;

    if (!reg_textos.test(nombre) || !reg_textos.test(apellidos)) {
        // Le pasamos el nombre del contenedor para ambos
        errores["contenedor-nombre"] = "El Nombre/Apellido debe contener solo letras";
    }


    //Campos para PAIS y CIUDAD
    let pais = document.getElementById("listaPaises").value;
    let opcionesPaises = document.getElementById("paises").options;// Obtener todas las opciones del datalist
    let ciudad = document.getElementById("campoCiudad").value;

    // Verificar si el valor seleccionado está entre las opciones disponibles
    let paisValido = false;
    for (let i=0; i<opcionesPaises.length; i++) {
        if (opcionesPaises[i].value === pais) {
            paisValido = true;
            break;
        }
    }

    if (!reg_textos.test(pais) || !reg_textos.test(ciudad) || !paisValido) {
        // Le pasamos el nombre del contenedor para ambos
        errores["localidad"] = "El País/Ciudad debe contener solo letras";
    }


    // Campo para el TELEFONO
    let telefono = document.getElementById("campoDeTelefono").value;
    if (telefono.length !== 9 || isNaN(telefono)) {
        errores["campoDeTelefono"] = "Teléfono no válido";
    }
    

    // Campo para la FECHA
    let fecha = document.getElementById("campoDeFecha").value;
    let hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    let fechaIngresada = new Date(fecha);

    if (fechaIngresada.toString() === "Invalid Date" || fechaIngresada > hoy) {
        errores["nacimiento"] = "La fecha ingresada no es válida";
    }
    

    // Campo para las POLÍTICAS
    let politicas = document.getElementById("campoPoliticas");
    if (!politicas.checked) {
        errores["politicas"] = "Debe aceptar nuestras políticas y condiciones";
    }

    // Verificar si el diccionario de campos y mensajes de errores no está vacío
    if (Object.keys(errores).length !== 0) {
        evento.preventDefault(); // Evitar el envío del formulario
        mostrarErrores(errores); // Mostrar todos los errores
    }
        
}
