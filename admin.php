<?php

$localizacion = "paneladmin";
require_once("funciones.php");
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["rol"] !== "admin") {
    header("Location: panelusuario.php");
    exit;
}

if (isset($_POST["id_prestamo"])) {

    $idPrestamo = (int) $_POST["id_prestamo"];

    devolverPrestamo($idPrestamo);

    header("Location: admin.php");
    exit;
}

$prestamos = obtenerPrestamosActivos();
$nombreUsuario = $_SESSION["usuario"];

require_once("cabecera.php");
?>
<main class="usuario-panel">

    <!-- CABECERA -->
    <section class="usuario-cabecera">

        <div class="usuario-identidad">

            <div class="usuario-avatar">
                <?= strtoupper(substr($nombreUsuario, 0, 1)) ?>
            </div>

            <div class="usuario-datos">

                <p class="usuario-bienvenida">
                    Panel de administración
                </p>

                <div class="usuario-nombre">

                    <h2>
                        <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>
                    </h2>

                </div>

            </div>

        </div>

    </section>


    <?php

    // ------------------------------------
    // CLASIFICAMOS LOS PRÉSTAMOS
    // ------------------------------------

    $hoy = new DateTime("today");
    $activos = [];
    $fueraDePlazo = [];
    $devolucionesHoy = [];

    foreach ($prestamos as $prestamo) {

        $activos[] = $prestamo;

        if (!empty($prestamo["fecha_devolucion"])) {

            $fechaDevolucion = new DateTime($prestamo["fecha_devolucion"]);

            // Fuera de plazo
            if ($fechaDevolucion < $hoy) {

                $fueraDePlazo[] = $prestamo;

            // Devolución prevista para hoy
            } elseif ($fechaDevolucion->format("Y-m-d") === $hoy->format("Y-m-d")) {

                $devolucionesHoy[] = $prestamo;
            }
        }
    }

    $totalActivos = count($activos);
    $totalFueraDePlazo = count($fueraDePlazo);
    $totalDevolucionesHoy = count($devolucionesHoy);

    ?>


    <!-- RESUMEN -->
    <section class="usuario-resumen">

        <div class="usuario-resumen-item">

            <span class="usuario-resumen-numero">
                <?= $totalActivos ?>
            </span>

            <span class="usuario-resumen-texto">
                Préstamos activos
            </span>

        </div>


        <div class="usuario-resumen-item">

            <span class="usuario-resumen-numero">
                <?= $totalFueraDePlazo ?>
            </span>

            <span class="usuario-resumen-texto">
                Fuera de plazo
            </span>

        </div>


        <div class="usuario-resumen-item">

            <span class="usuario-resumen-numero">
                <?= $totalDevolucionesHoy ?>
            </span>

            <span class="usuario-resumen-texto">
                Vencen hoy
            </span>

        </div>

    </section>


    <!-- PRÉSTAMOS PENDIENTES -->
    <section class="usuario-seccion">

        <div class="usuario-seccion-cabecera">

            <h3>
                Préstamos pendientes de devolución
            </h3>

            <span>
                <?= $totalActivos ?> préstamo(s)
            </span>

        </div>


        <?php if (!empty($activos)): ?>

            <div class="prestamos-lista">

                <?php foreach ($activos as $prestamo): ?>

                    <?php

                   $estadoClase = "activo";
                $estadoTexto = "Activo";

                if (!empty($prestamo["fecha_devolucion"])) {

                    $fechaDevolucion = new DateTime($prestamo["fecha_devolucion"]);

                    if ($fechaDevolucion < $hoy) {

                        $estadoClase = "urgente";
                        $estadoTexto = "Fuera de plazo";

                    } elseif (
                        $fechaDevolucion->format("Y-m-d") ===
                        $hoy->format("Y-m-d")
                    ) {

                        $estadoClase = "urgente";
                        $estadoTexto = "Devolución hoy";
                    }
                }

                    ?>


                    <article class="prestamo">

                       <div class="prestamo-info">

    <h4>
        <?= htmlspecialchars(
            $prestamo["titulo"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </h4>

    <p class="prestamo-autor">
        <?= htmlspecialchars(
            $prestamo["autor"],
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </p>

    <p class="prestamo-fecha">

        Usuario:

        <strong>
            <?= htmlspecialchars(
                $prestamo["usuario"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>
        </strong>

    </p>

    <p class="prestamo-fecha">

        Préstamo:

        <strong>
            <?= (new DateTime(
                $prestamo["fecha_prestamo"]
            ))->format("d/m/Y") ?>
        </strong>

    </p>

    <p class="prestamo-fecha">

        Devolución:

        <strong>
            <?= (new DateTime(
                $prestamo["fecha_devolucion"]
            ))->format("d/m/Y") ?>
        </strong>

    </p>

</div>

                        <div class="prestamo-acciones">

                            <span class="prestamo-estado <?= $estadoClase ?>">
                                <?= $estadoTexto ?>
                            </span>


                            <form
                                method="post"
                                action="admin.php"
                            >

                                <input
                                    type="hidden"
                                    name="id_prestamo"
                                    value="<?= $prestamo["id"] ?>"
                                >

                                <button
                                    type="button"
                                    class="boton-principal boton-devolucion"                                   
                                    data-id-prestamo="<?= $prestamo["id"] ?>"
                                >
                                    Registrar devolución
                                </button>

                            </form>

                        </div>

                    </article>


                <?php endforeach; ?>

            </div>


        <?php else: ?>

            <p class="usuario-aviso">
                No hay préstamos pendientes de devolución actualmente.
            </p>

        <?php endif; ?>

    </section>

</main>

<div id="modal-devolucion" class="modal-reserva" hidden>

    <div class="modal-reserva-fondo" id="cerrar-devolucion-fondo"></div>

    <div class="modal-reserva-contenido">

        <button
            type="button"
            class="modal-reserva-cerrar"
            id="cerrar-devolucion"
            aria-label="Cerrar"
        >
            ×
        </button>

        <h2>Registrar devolución</h2>

        <p class="modal-reserva-texto">
            ¿Quieres registrar la devolución de este libro?
        </p>

        <div class="modal-reserva-acciones">

            <form method="post" action="admin.php">

                <input type="hidden" name="id_prestamo" id="id-prestamo-devolucion">

                <button type="button" class="modal-reserva-cancelar" id="cancelar-devolucion">
                    Cancelar
                </button>

                <button type="submit" class="boton-principal">
                    Confirmar devolución
                </button>

            </form>

        </div>

    </div>

</div>

<script src="js/devolverlibro.js"></script>

<?php
require_once("footer.php");
?>