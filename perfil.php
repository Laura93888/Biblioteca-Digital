<?php

$localizacion = "perfil";

session_start();

require_once("funciones.php");


// ------------------------------------
// COMPROBAR SESIÓN
// ------------------------------------

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}


// ------------------------------------
// DATOS DEL USUARIO
// ------------------------------------

$idUsuario = $_SESSION["usuario_id"];

$nombreUsuario = $_SESSION["usuario"];
$emailUsuario = $_SESSION["email"];

$error = "";
$exito = "";


// ------------------------------------
// PROCESAR FORMULARIOS
// ------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";


    // --------------------------------
    // CAMBIAR DATOS PERSONALES
    // --------------------------------

    if ($accion === "datos") {

        $nombre = trim($_POST["nombre"] ?? "");
        $email = trim($_POST["email"] ?? "");


        if ($nombre === "" || $email === "") {

            $error = "Por favor, completa todos los campos.";

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = "Introduce un correo electrónico válido.";

        } elseif (emailExisteOtroUsuario($email, $idUsuario)) {

            $error = "Ese correo electrónico ya está registrado.";

        } else {

            actualizarDatosUsuario(
                $idUsuario,
                $nombre,
                $email
            );

            // Actualizamos también la sesión
            $_SESSION["usuario"] = $nombre;
            $_SESSION["email"] = $email;

            $nombreUsuario = $nombre;
            $emailUsuario = $email;

            $exito = "Tus datos se han actualizado correctamente.";
        }
    }


    // --------------------------------
    // CAMBIAR CONTRASEÑA
    // --------------------------------

    elseif ($accion === "contrasena") {

        $contrasenaActual = $_POST["contrasena_actual"] ?? "";
        $nuevaContrasena = $_POST["nueva_contrasena"] ?? "";
        $repetirContrasena = $_POST["repetir_contrasena"] ?? "";


        if (
            $contrasenaActual === "" ||
            $nuevaContrasena === "" ||
            $repetirContrasena === ""
        ) {

            $error = "Por favor, completa todos los campos de contraseña.";

        } elseif ($nuevaContrasena !== $repetirContrasena) {

            $error = "Las nuevas contraseñas no coinciden.";

        } elseif (strlen($nuevaContrasena) < 6) {

            $error = "La nueva contraseña debe tener al menos 6 caracteres.";

        } else {

            $contrasenaGuardada = obtenerContrasenaUsuario($idUsuario);


            if (
                $contrasenaGuardada === null ||
                !password_verify(
                    $contrasenaActual,
                    $contrasenaGuardada
                )
            ) {

                $error = "La contraseña actual no es correcta.";

            } else {

                actualizarContrasenaUsuario(
                    $idUsuario,
                    $nuevaContrasena
                );

                $exito = "Tu contraseña se ha cambiado correctamente.";
            }
        }
    }
}


require_once("cabecera.php");

?>


<main class="perfil-pagina">

    <section class="perfil-cabecera">

        <div>

            <p class="eyebrow">
                Tu cuenta
            </p>

            <h2>
                Tu perfil
            </h2>

            <p class="perfil-introduccion">
                Gestiona tus datos personales y mantén tu cuenta actualizada.
            </p>

        </div>


        <a href="panelusuario.php" class="perfil-volver">
            ← Volver a mi área
        </a>

    </section>


    <?php if ($error): ?>

        <p class="perfil-mensaje perfil-error">
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>


    <?php if ($exito): ?>

        <p class="perfil-mensaje perfil-exito">
            <?= htmlspecialchars($exito, ENT_QUOTES, "UTF-8") ?>
        </p>

    <?php endif; ?>


    <!-- INFORMACIÓN PERSONAL -->

    <section class="perfil-seccion">

        <div class="perfil-seccion-cabecera">

            <div>

                <p class="perfil-etiqueta">
                    Información personal
                </p>

                <h3>
                    Tus datos
                </h3>

            </div>

        </div>


        <form method="POST" class="perfil-formulario">

            <input
                type="hidden"
                name="accion"
                value="datos"
            >


            <div class="perfil-campo">

                <label for="nombre">
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>"
                    required
                >

            </div>


            <div class="perfil-campo">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($emailUsuario, ENT_QUOTES, "UTF-8") ?>"
                    required
                >

            </div>


            <div class="perfil-formulario-pie">

                <p>
                    Estos datos se utilizarán para identificar tu cuenta en Bookify.
                </p>

                <button
                    type="submit"
                    class="perfil-boton"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </section>


    <!-- SEGURIDAD -->

    <section class="perfil-seccion perfil-seguridad">

        <div class="perfil-seccion-cabecera">

            <div>

                <p class="perfil-etiqueta">
                    Seguridad
                </p>

                <h3>
                    Cambiar contraseña
                </h3>

            </div>

        </div>


        <form method="POST" class="perfil-formulario">

            <input
                type="hidden"
                name="accion"
                value="contrasena"
            >


            <div class="perfil-campo">

                <label for="contrasena_actual">
                    Contraseña actual
                </label>

                <input
                    type="password"
                    id="contrasena_actual"
                    name="contrasena_actual"
                    autocomplete="current-password"
                    required
                >

            </div>


            <div class="perfil-campo">

                <label for="nueva_contrasena">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="nueva_contrasena"
                    name="nueva_contrasena"
                    autocomplete="new-password"
                    required
                >

            </div>


            <div class="perfil-campo">

                <label for="repetir_contrasena">
                    Repetir nueva contraseña
                </label>

                <input
                    type="password"
                    id="repetir_contrasena"
                    name="repetir_contrasena"
                    autocomplete="new-password"
                    required
                >

            </div>


            <div class="perfil-formulario-pie">

                <p>
                    Por seguridad, necesitarás introducir tu contraseña actual
                    antes de establecer una nueva.
                </p>

                <button
                    type="submit"
                    class="perfil-boton"
                >
                    Cambiar contraseña
                </button>

            </div>

        </form>

    </section>

</main>


<?php

require_once("footer.php");

?>

