document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formularioInicioSesion").addEventListener('submit', function(evento) {
        // Validar el correo con expresión regular
        let correo = document.getElementById("campoUsuario").value;
        const reg_correo = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
        if (!reg_correo.test(correo)) {
            alert("Por favor, introduce un correo electrónico válido.");
            evento.preventDefault(); // Evitar el envío del formulario
            return; // Detener la ejecución del código
        }

        // Validar la longitud de la contraseña
        let contraseña = document.getElementById("campoContraseña").value;
        if (contraseña.length < 8) {
            alert("La contraseña debe tener al menos 8 caracteres.");
            evento.preventDefault(); // Evitar el envío del formulario
            return; // Detener la ejecución del código
        }
        
    });
});
