<?php

session_start();

require_once("db.php");

// Si el usuario ya ha iniciado sesión,
// no necesita registrarse otra vez.
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$error = "";
$exito = "";

$nombre = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $contraseña = $_POST["contraseña"] ?? "";
    $repetirContraseña = $_POST["repetir_contraseña"] ?? "";


    // =========================
    // VALIDAR NOMBRE
    // =========================

    if ($nombre === "") {

        $error = "El nombre es obligatorio.";

    } elseif (strlen($nombre) < 2) {

        $error = "El nombre debe tener al menos 2 caracteres.";

    } elseif (strlen($nombre) > 50) {

        $error = "El nombre no puede superar los 50 caracteres.";

    } elseif (!preg_match("/^[\p{L}\s'-]+$/u", $nombre)) {

        $error = "El nombre solo puede contener letras, espacios, apóstrofes y guiones.";
    }


    // =========================
    // VALIDAR EMAIL
    // =========================

    elseif ($email === "") {

        $error = "El email es obligatorio.";

    } elseif (strlen($email) > 100) {

        $error = "El email no puede superar los 100 caracteres.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Introduce un email válido.";
    }


    // =========================
    // VALIDAR CONTRASEÑA
    // =========================

    elseif ($contraseña === "") {

        $error = "La contraseña es obligatoria.";

    } elseif (strlen($contraseña) < 6) {

        $error = "La contraseña debe tener al menos 6 caracteres.";

    } elseif (strlen($contraseña) > 255) {

        $error = "La contraseña no puede superar los 255 caracteres.";

    } elseif (!preg_match("/[A-Z]/", $contraseña)) {

        $error = "La contraseña debe contener al menos una letra mayúscula.";

    } elseif (!preg_match("/[a-z]/", $contraseña)) {

        $error = "La contraseña debe contener al menos una letra minúscula.";

    } elseif (!preg_match("/[0-9]/", $contraseña)) {

        $error = "La contraseña debe contener al menos un número.";
    }


    // =========================
    // REPETIR CONTRASEÑA
    // =========================

    elseif ($repetirContraseña === "") {

        $error = "Debes repetir la contraseña.";

    } elseif ($contraseña !== $repetirContraseña) {

        $error = "Las contraseñas no coinciden.";
    }


    // =========================
    // CREAR CUENTA
    // =========================

    else {

        try {

           $usuarioExistente = $bbdd->comprobarusuario($email);

            if ($usuarioExistente) {

                $error = "Ya existe una cuenta con ese email.";

            } else {

                // Convertimos la contraseña en un hash seguro
                $contraseñaHash = password_hash(
                    $contraseña,
                    PASSWORD_DEFAULT
                );

                // Crear el usuario
                $bbdd->crearUsuario($nombre, $email, $contraseñaHash);

                $exito = "Cuenta creada correctamente.";

                // Limpiamos los campos
                $nombre = "";
                $email = "";
            }


        } catch (PDOException $e) {

            // Durante el desarrollo mostramos el error real
            $error = "Error de base de datos: " . $e->getMessage();
        }
    }
}


$localizacion = "";

require_once("cabecera.php");

?>

<main class="alta-pagina" aria-labelledby="registro-title">

    <section class="alta-portada">

        <div class="alta-encabezado">

            <p class="eyebrow">
                Únete a Bookify
            </p>

            <h2 id="registro-title">
                Crea tu<br><em>cuenta.</em>
            </h2>

            <span class="alta-regla" aria-hidden="true"></span>

            <p class="alta-introduccion">
                Tu próxima lectura empieza aquí. Crea tu espacio para guardar libros y gestionar tus préstamos.
            </p>

        </div>


        <div class="alta-formulario">


            <?php if ($error !== ""): ?>

                <div class="alta-error" role="alert">

                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>

                </div>

            <?php endif; ?>


            <?php if ($exito !== ""): ?>

                <div class="alta-exito" role="status">

                    <?= htmlspecialchars($exito, ENT_QUOTES, "UTF-8") ?>

                    <p>
                        <a href="login.php">
                            Iniciar sesión
                        </a>
                    </p>

                </div>

            <?php endif; ?>


            <?php if ($exito === ""): ?>

                <form method="post" action="registro.php">


                    <!-- NOMBRE -->

                    <div class="alta-campo alta-campo-nombre">

                        <label for="nombre">
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="<?= htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") ?>"
                            autocomplete="name"
                            maxlength="50"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="alta-campo">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
                            autocomplete="email"
                            maxlength="100"
                            required
                        >

                    </div>


                    <!-- CONTRASEÑA -->

                    <div class="alta-campo">

                        <label for="contraseña">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            id="contraseña"
                            name="contraseña"
                            autocomplete="new-password"
                            minlength="6"
                            maxlength="255"
                            required
                        >

                    </div>


                    <!-- REPETIR CONTRASEÑA -->

                    <div class="alta-campo">

                        <label for="repetir_contraseña">
                            Repetir contraseña
                        </label>

                        <input
                            type="password"
                            id="repetir_contraseña"
                            name="repetir_contraseña"
                            autocomplete="new-password"
                            minlength="6"
                            maxlength="255"
                            required
                        >

                    </div>


                    <button class="alta-boton" type="submit">

                        <span>
                            Crear cuenta
                        </span>

                        <span aria-hidden="true">
                            ↗
                        </span>

                    </button>


                </form>


                <p class="alta-login">

                    ¿Ya tienes una cuenta?

                    <a href="login.php">
                        Inicia sesión
                    </a>

                </p>


            <?php endif; ?>

        </div>

    </section>

</main>

<?php
require_once("footer.php");
?>