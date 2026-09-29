<?php

session_start();

require_once("funciones.php");

// Si el usuario ya ha iniciado sesión,
// no tiene sentido mostrarle el formulario de login.
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $contraseña = $_POST["contraseña"] ?? "";

    // Comprobamos que se hayan rellenado los campos
    if ($email === "" || $contraseña === "") {

        $error = "Debes completar todos los campos.";

    } else {

        try {

            $usuario = buscarUsuario($email);
           

            // Comprobamos que exista y que la contraseña sea correcta
            if ($usuario && password_verify($contraseña, $usuario["contrasena"])) {

                // Guardamos los datos necesarios en la sesión
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario"] = $usuario["nombre"];
                $_SESSION["email"] = $usuario["email"];
                $_SESSION["rol"] = $usuario["rol"];

                // Volvemos al panel de usuario
                if ($usuario["rol"] === "admin") {
                    header("Location: admin.php");
                    exit;
                }

                header("Location: panelusuario.php");
                exit;

            } else {

                $error = "El email o la contraseña no son correctos.";
            }

        } catch (PDOException $e) {

            $error =  $error = $e->getMessage();
        }
    }
}

$localizacion = "";
require_once("cabecera.php");

?>

<main class="acceso-pagina" aria-labelledby="login-title">

    <section class="acceso-portada">

    
        <div class="acceso-encabezado">
            <p class="eyebrow">Tu biblioteca</p>

            <h2 id="login-title">Bienvenido<br>de nuevo</h2>

            <span class="acceso-regla" aria-hidden="true"></span>

            <p class="acceso-introduccion">
                Inicia sesión para consultar tus libros, gestionar tus préstamos y continuar explorando Bookify.
            </p>
        </div>

        <div class="acceso-formulario">

            <?php if ($error !== ""): ?>

                <div class="acceso-error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>

            <?php endif; ?>


            <form method="post" action="login.php">

                <div class="acceso-campo">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        autocomplete="email"
                        required
                    >

                </div>


                <div class="acceso-campo">

                    <label for="contraseña">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="contraseña"
                        name="contraseña"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <button class="acceso-boton" type="submit">
                    <span>Iniciar sesión</span>
                    <span aria-hidden="true">↗</span>
                </button>

            </form>


            <p class="acceso-registro">
                ¿Todavía no tienes una cuenta?
                <a href="registro.php">Regístrate</a>
            </p>

        </div>

    </section>

</main>

<?php
require_once("footer.php");
?>



