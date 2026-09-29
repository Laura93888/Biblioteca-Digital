<?php

require_once("funciones.php");

$localizacion = "catalogo";
require_once("cabecera.php");

$id = (int)($_GET["id"] ?? 0);

$libro = obtenerLibroPorId($id);

if (!$libro) {
    echo '<main class="ficha-pagina">';
    echo '<section class="libro-no-encontrado">';
    echo '<h2>Libro no encontrado</h2>';
    echo '<a href="index.php" class="boton-principal">Volver al catálogo</a>';
    echo '</section>';
    echo '</main>';

    require_once("footer.php");
    exit;
}

$disponible = comprobarDisponibilidadLibro($id);
$reservaFallida = isset($_GET["reserva"]) && $_GET["reserva"] === "no";

?>

<main class="ficha-pagina">

    <article class="ficha-libro">

        <!-- CABECERA -->
        <header class="ficha-cabecera">

            <h2 class="ficha-titulo">
                <?= htmlspecialchars($libro["titulo"]) ?>
            </h2>

            <p class="ficha-autor">
                <?= htmlspecialchars($libro["autor"]) ?>
            </p>

        </header>


        <!-- LÍNEA DECORATIVA -->
        <div class="ficha-separador">
            <span></span>
        </div>


        <!-- INFORMACIÓN PRINCIPAL -->
        <section class="ficha-contenido">

            <div class="ficha-portada">

                <div class="portada-ficha">

                    <img
                        src="imágenes/portadas/<?= htmlspecialchars($libro["imagen"]) ?>"
                        alt="Portada de <?= htmlspecialchars($libro["titulo"]) ?>"
                    >

                </div>

            </div>


            <div class="ficha-informacion">

                <p class="ficha-descripcion">
                    <?= htmlspecialchars($libro["descripcion"]) ?>
                </p>


                <div class="ficha-datos">

                    <div class="ficha-dato">
                        <span>Autor</span>
                        <strong>
                            <?= htmlspecialchars($libro["autor"]) ?>
                        </strong>
                    </div>

                    <div class="ficha-dato">
                        <span>Categoría</span>
                        <strong>
                            <?= htmlspecialchars($libro["categoria"]) ?>
                        </strong>
                    </div>

                    <div class="ficha-dato">
                        <span>Año de publicación</span>
                        <strong>
                            <?= htmlspecialchars($libro["anio"]) ?>
                        </strong>
                    </div>

                </div>

            </div>

        </section>

        <!-- DISPONIBILIDAD Y RESERVA -->
<section class="ficha-reserva">

    <div class="ficha-estado">

        <span class="ficha-estado-etiqueta">
            Disponibilidad
        </span>

        <?php if ($reservaFallida): ?>

        <p class="aviso-reserva">
        La reserva no se ha podido completar.
        Otro usuario ha reservado este libro antes.
        </p>

        <?php endif; ?>

        <?php if ($disponible): ?>

            <p class="estado-disponible">
                <span class="estado-punto"></span>
                Disponible para reservar
            </p>

        <?php else: ?>

            <p class="estado-no-disponible">
                <span class="estado-punto"></span>
                Actualmente no disponible
            </p>

        <?php endif; ?>

    </div>


    <?php if ($disponible): ?>

        <form
            method="post"
            action="reservar.php"
            id="form-reserva"
        >
            <input
                type="hidden"
                name="id_libro"
                value="<?= $id ?>"
            >

            <button
                type="button"
                class="boton-principal boton-reserva"
                id="abrir-reserva"
            >
                Reservar este libro
            </button>
        </form>

    <?php else: ?>

        <button
            type="button"
            class="boton-principal boton-reserva boton-deshabilitado"
            disabled
        >
            No disponible
        </button>

    <?php endif; ?>

</section>


        <!-- VOLVER -->
        <footer class="ficha-footer">

            <a href="index.php" class="ficha-volver">
                ← Volver al catálogo
            </a>

        </footer>

    </article>

    <!-- MODAL DE CONFIRMACIÓN -->
<div
    class="modal-reserva"
    id="modal-reserva"
    hidden
>
    <div
        class="modal-reserva-fondo"
        id="cerrar-reserva-fondo"
    ></div>

    <section
        class="modal-reserva-contenido"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-reserva-titulo"
    >

        <button
            type="button"
            class="modal-reserva-cerrar"
            id="cerrar-reserva"
            aria-label="Cerrar"
        >
            ×
        </button>

        <p class="eyebrow">
            Reservar libro
        </p>

        <h2 id="modal-reserva-titulo">
            ¿Quieres reservar<br>
            <em><?= htmlspecialchars($libro["titulo"]) ?></em>?
        </h2>

        <p class="modal-reserva-texto">
            Reserva el libro y recógelo físicamente en la biblioteca. Al confirmar, aparecerá en tus préstamos activos.
        </p>

        <div class="modal-reserva-acciones">

            <button
                type="button"
                class="modal-reserva-cancelar"
                id="cancelar-reserva"
            >
                Cancelar
            </button>

            <button
                type="submit"
                form="form-reserva"
                class="boton-principal"
            >
                Confirmar reserva
            </button>

        </div>

    </section>
</div>

</main>

<script src="js/reserva.js"></script>

<?php
require_once("footer.php");
?>

