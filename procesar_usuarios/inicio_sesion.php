<?php if (!isset($_SESSION['nombre_usuario'])) : ?>
    <!-- Formulario de inicio de sesión -->
    <section>
        <form id="formularioInicioSesion" action="../procesar_usuarios/inicio_usuario.php" method="post">
            <input class="credenciales" type="email" id="campoUsuario" name="campoUsuario" placeholder="Usuario">
            <input class="credenciales" type="password" id="campoContraseña" name="campoContraseña" placeholder="Contraseña"> <br>
            <button style="cursor: pointer;" type="submit" id="botonInicioSesion" value="Iniciar sesión">Iniciar sesión</button>  
        </form>
        <a id="registro" href="../pe1/altausuarios.html"><p>Crear cuenta</p></a>
    </section>  
<?php else : ?>
    <!-- Información del usuario y botón de cierre de sesión -->
    <section style="display: flex; flex-direction: column; ">
        <div style="display: flex; margin: auto 30px; font-family: sans-serif; ">
            <p style="margin: 0px 10px 15px; color:#87CEFA; font-weight: bold; text-transform:uppercase"><?= $_SESSION['nombre_usuario']?></p>
            <p style="margin: 0px 10px 15px; font-style: italic;color:white;"><?= $_SESSION['tipo']?></p>
        </div>
        <form action="../procesar_usuarios/cerrar_sesion.php" method="post" >
            <button type="submit" value="Cerrar Sesión" 
                style="color: white; background-color: gray; cursor: pointer;
                border: 1px solid white; padding: 5px 0; font-weight: bold; 
                font-size: 15px; width: 100%;">Cerrar Sesión</button>
        </form>
    </section>    
<?php endif; ?>      