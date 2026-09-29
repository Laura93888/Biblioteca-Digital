<?php
$localizacion="panelusuario";
session_start();

require_once("funciones.php");

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["rol"]==="admin"){
    header("Location:admin.php");
    exit;
}

$prestamos = cargarprestamos($_SESSION["usuario_id"]);
$reservaConfirmada = isset($_GET["reserva"]) && $_GET["reserva"] === "ok";
// Datos del usuario
$idUsuario = $_SESSION["usuario_id"];
$nombreUsuario = $_SESSION["usuario"];
$emailUsuario = $_SESSION["email"];

// ------------------------------------
// CLASIFICAMOS LOS PRÉSTAMOS
// ------------------------------------

$activos = [];
$proximos = [];
$vencidos = [];
$historial = [];

$hoy = new DateTime("today");
$limiteProximo = new DateTime("today");
$limiteProximo->modify("+3 days");


foreach ($prestamos as $prestamo) {

    // Si el préstamo ya está devuelto
    if ($prestamo["estado"] === "devuelto") {

        $historial[] = $prestamo;

        continue;
    }


    // Si está activo
    if ($prestamo["estado"] === "activo") {

        $activos[] = $prestamo;


        // Comprobamos que tenga fecha de devolución
        if (!empty($prestamo["fecha_devolucion"])) {

            $fechaDevolucion = new DateTime($prestamo["fecha_devolucion"]);


            // Si ya ha pasado la fecha de devolución
            if ($fechaDevolucion < $hoy) {

                $vencidos[] = $prestamo;

            }

            // Si vence entre hoy y los próximos 3 días
            elseif ($fechaDevolucion <= $limiteProximo) {

                $proximos[] = $prestamo;

            }
        }
    }
}


// ------------------------------------
// CONTADORES
// ------------------------------------

$totalActivos = count($activos);
$totalProximos = count($proximos);
$totalVencidos = count($vencidos);
$totalHistorial = count($historial);
$totalPrestamos = count($prestamos);

require_once("cabecera.php");

?>

<main class="usuario-panel">

   <section class="usuario-cabecera">

    <div class="usuario-identidad">

        <div class="usuario-avatar">
            <?= strtoupper(substr($nombreUsuario, 0, 1)) ?>
        </div>

        <div class="usuario-datos">
    <p class="usuario-bienvenida">Bienvenido a tu biblioteca</p>

    <div class="usuario-nombre">
        <h2>
            <?= htmlspecialchars($nombreUsuario, ENT_QUOTES, "UTF-8") ?>
        </h2>

        <a href="perfil.php" class="usuario-perfil">
            Editar perfil <span aria-hidden="true">↗</span>
        </a>
    </div>

</div>

    </div>

</section>

    <?php if ($reservaConfirmada): ?>
        <p class="usuario-aviso" role="status">
            Reserva confirmada. Recuerda recoger el libro físicamente en la biblioteca.
        </p>
    <?php endif; ?>

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
                <?= $totalProximos ?>
            </span>

            <span class="usuario-resumen-texto">
                Próximos a vencer
            </span>
        </div>


        <div class="usuario-resumen-item">
            <span class="usuario-resumen-numero">
                <?= $totalVencidos ?>
            </span>

            <span class="usuario-resumen-texto">
                Vencidos
            </span>
        </div>

    </section>


    <!-- PRÓXIMAS DEVOLUCIONES -->
    <?php if (!empty($proximos)): ?>

        <section class="usuario-seccion">

            <div class="usuario-seccion-cabecera">
                <h3>Próximas devoluciones</h3>
                <span><?= count($proximos) ?> préstamo(s)</span>
            </div>


            <div class="prestamos-lista">

                <?php foreach ($proximos as $prestamo): ?>

                    <article class="prestamo">

                        <div class="prestamo-info">

                            <h4>
                                <?= htmlspecialchars($prestamo["titulo"], ENT_QUOTES, "UTF-8") ?>
                            </h4>

                            <p class="prestamo-autor">
                                <?= htmlspecialchars($prestamo["autor"], ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <p class="prestamo-fecha">
                                Fecha de devolución:
                                <strong>
                                    <?= (new DateTime($prestamo["fecha_devolucion"]))->format("d/m/Y") ?>
                                </strong>
                            </p>

                        </div>

                        <span class="prestamo-estado urgente">
                            Próximo a vencer
                        </span>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>


    <!-- PRÉSTAMOS VENCIDOS -->
    <?php if (!empty($vencidos)): ?>

        <section class="usuario-seccion">

            <div class="usuario-seccion-cabecera">
                <h3>Préstamos vencidos</h3>
                <span><?= count($vencidos) ?> préstamo(s)</span>
            </div>


            <div class="prestamos-lista">

                <?php foreach ($vencidos as $prestamo): ?>

                    <article class="prestamo">

                        <div class="prestamo-info">

                            <h4>
                                <?= htmlspecialchars($prestamo["titulo"], ENT_QUOTES, "UTF-8") ?>
                            </h4>

                            <p class="prestamo-autor">
                                <?= htmlspecialchars($prestamo["autor"], ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <p class="prestamo-fecha">
                                Fecha de devolución:
                                <strong>
                                    <?= (new DateTime($prestamo["fecha_devolucion"]))->format("d/m/Y") ?>
                                </strong>
                            </p>

                        </div>

                        <span class="prestamo-estado urgente">
                            Vencido
                        </span>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    <?php endif; ?>


    <!-- TODOS LOS PRÉSTAMOS ACTIVOS -->
    <section class="usuario-seccion">

        <div class="usuario-seccion-cabecera">
            <h3>Mis préstamos activos</h3>
            <span><?= $totalActivos ?> préstamo(s)</span>
        </div>


        <?php if (!empty($activos)): ?>

            <div class="prestamos-lista">

                <?php foreach ($activos as $prestamo): ?>

                    <article class="prestamo">

                        <div class="prestamo-info">

                            <h4>
                                <?= htmlspecialchars($prestamo["titulo"], ENT_QUOTES, "UTF-8") ?>
                            </h4>

                            <p class="prestamo-autor">
                                <?= htmlspecialchars($prestamo["autor"], ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <p class="prestamo-fecha">
                                Préstamo:
                                <strong>
                                    <?= (new DateTime($prestamo["fecha_prestamo"]))->format("d/m/Y") ?>
                                </strong>
                            </p>

                            <?php if (!empty($prestamo["fecha_devolucion"])): ?>

                                <p class="prestamo-fecha">
                                    Devolución:
                                    <strong>
                                        <?= (new DateTime($prestamo["fecha_devolucion"]))->format("d/m/Y") ?>
                                    </strong>
                                </p>

                            <?php else: ?>

                                <p class="prestamo-fecha">
                                    Fecha de devolución pendiente
                                </p>

                            <?php endif; ?>

                        </div>


                        <?php
                        if (!empty($prestamo["fecha_devolucion"])):

                            $fechaDevolucion = new DateTime($prestamo["fecha_devolucion"]);

                            if ($fechaDevolucion < $hoy):
                        ?>

                                <span class="prestamo-estado urgente">
                                    Vencido
                                </span>

                            <?php elseif ($fechaDevolucion <= $limiteProximo): ?>

                                <span class="prestamo-estado urgente">
                                    Próximo a vencer
                                </span>

                            <?php else: ?>

                                <span class="prestamo-estado">
                                    Activo
                                </span>

                            <?php endif; ?>

                        <?php else: ?>

                            <span class="prestamo-estado">
                                Activo
                            </span>

                        <?php endif; ?>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="usuario-aviso">
                No tienes préstamos activos actualmente.
            </p>

        <?php endif; ?>

    </section>


    <!-- HISTORIAL -->
    <section class="usuario-seccion">

        <div class="usuario-seccion-cabecera">
            <h3>Historial de préstamos</h3>
            <span><?= $totalHistorial ?> préstamo(s)</span>
        </div>


        <?php if (!empty($historial)): ?>

            <div class="prestamos-lista">

                <?php foreach ($historial as $prestamo): ?>

                    <article class="prestamo">

                        <div class="prestamo-info">

                            <h4>
                                <?= htmlspecialchars($prestamo["titulo"], ENT_QUOTES, "UTF-8") ?>
                            </h4>

                            <p class="prestamo-autor">
                                <?= htmlspecialchars($prestamo["autor"], ENT_QUOTES, "UTF-8") ?>
                            </p>

                            <p class="prestamo-fecha">
                                Préstamo:
                                <strong>
                                    <?= (new DateTime($prestamo["fecha_prestamo"]))->format("d/m/Y") ?>
                                </strong>
                            </p>

                            <?php if (!empty($prestamo["fecha_devolucion"])): ?>

                                <p class="prestamo-fecha">
                                    Devolución:
                                    <strong>
                                        <?= (new DateTime($prestamo["fecha_devolucion"]))->format("d/m/Y") ?>
                                    </strong>
                                </p>

                            <?php endif; ?>

                        </div>

<div class="prestamo-estado-historial">

    <span class="prestamo-estado devuelto">
        Devuelto
    </span>

    <?php if ($prestamo["fuera_de_plazo"]): ?>

        <span class="prestamo-resultado fuera-plazo">
            Devolución fuera de plazo
        </span>

            <?php else: ?>

                <span class="prestamo-resultado a-tiempo">
                    Devuelto a tiempo
                </span>

            <?php endif; ?>

        </div>
                    </article>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <p class="usuario-aviso">
                Todavía no tienes préstamos en tu historial.
            </p>

        <?php endif; ?>

    </section>


</main>

<?php
require_once("footer.php");
?>