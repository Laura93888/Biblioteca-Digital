<?php
require_once("funciones.php");

$localizacion="indice";
$libros = obtenerLibros();

require_once("cabecera.php");
?>

<main class="home" aria-labelledby="home-title">
  <section class="home-hero">
    <div class="home-hero-texto">
      <p class="home-etiqueta">Biblioteca digital Bookify</p>
      <h2 id="home-title">Tu próxima historia<br><em>empieza aquí</em></h2>
      <p class="home-intro">Explora nuestra colección, descubre nuevos autores y reserva tus libros para recogerlos físicamente en la biblioteca.</p>
      <a class="home-boton" href="catalogo.php">Explorar catálogo <span aria-hidden="true">↗</span></a>
    </div>
    <div class="home-hero-imagen">
      <img src="imágenes/biblioteca.jpg" alt="Interior de la biblioteca de Bookify">
    </div>
  </section>

<section class="home-categorias" id="categorias" aria-labelledby="categorias-title">

    <div class="home-seccion-cabecera">
        <p class="home-etiqueta">Empieza por una idea</p>
        <h2 id="categorias-title">¿Qué te apetece leer?</h2>
    </div>

    <div class="categorias-lista">

        <a href="catalogo.php?categoria=Ficción" class="categoria-enlace">
            <span class="categoria-imagen">
                <img src="imágenes/categorias/ficcion.png" alt="" aria-hidden="true">
            </span>

            <span class="categoria-contenido">
                <strong>Ficción</strong>
                <span class="categoria-descripcion">
                    Historias que nos acercan a otras vidas y posibilidades.
                </span>
            </span>

            <span class="categoria-flecha" aria-hidden="true">↗</span>
        </a>


        <a href="catalogo.php?categoria=Fantasía" class="categoria-enlace">
            <span class="categoria-imagen">
                <img src="imágenes/categorias/fantasia.png" alt="" aria-hidden="true">
            </span>

            <span class="categoria-contenido">
                <strong>Fantasía</strong>
                <span class="categoria-descripcion">
                    Mundos imaginarios, aventuras y criaturas extraordinarias.
                </span>
            </span>

            <span class="categoria-flecha" aria-hidden="true">↗</span>
        </a>


        <a href="catalogo.php?categoria=Ciencia" class="categoria-enlace">
            <span class="categoria-imagen">
                <img src="imágenes/categorias/ciencia.png" alt="" aria-hidden="true">
            </span>

            <span class="categoria-contenido">
                <strong>Ciencia</strong>
                <span class="categoria-descripcion">
                    Preguntas, descubrimientos y formas de entender el mundo.
                </span>
            </span>

            <span class="categoria-flecha" aria-hidden="true">↗</span>
        </a>


        <a href="catalogo.php?categoria=Historia" class="categoria-enlace">
            <span class="categoria-imagen">
                <img src="imágenes/categorias/historia.png" alt="" aria-hidden="true">
            </span>

            <span class="categoria-contenido">
                <strong>Historia</strong>
                <span class="categoria-descripcion">
                    El pasado como una ventana para comprender el presente.
                </span>
            </span>

            <span class="categoria-flecha" aria-hidden="true">↗</span>
        </a>


        <a href="catalogo.php?categoria=Arte" class="categoria-enlace">
            <span class="categoria-imagen">
                <img src="imágenes/categorias/arte.png" alt="" aria-hidden="true">
            </span>

            <span class="categoria-contenido">
                <strong>Arte</strong>
                <span class="categoria-descripcion">
                    Miradas, obras y sensibilidad para inspirar nuevas ideas.
                </span>
            </span>

            <span class="categoria-flecha" aria-hidden="true">↗</span>
        </a>

    </div>

</section>


  <section class="home-descubre home-libros-destacados" aria-labelledby="destacados-title">
    <div class="home-seccion-cabecera home-descubre-cabecera">
      <div>
        <p class="home-etiqueta">Una selección para empezar</p>
        <h2 id="destacados-title">Descubre algunos libros de nuestra colección</h2>
        
      </div>
    </div>
    <div class="libros-destacados-lista">
      <?php foreach (array_slice($libros, 0, 4) as $libro): ?>
        
        <article class="libro-destacado">
          <a href="libro.php?id=<?= (int) $libro["id"] ?>" aria-label="Ver <?= htmlspecialchars($libro["titulo"], ENT_QUOTES, "UTF-8") ?>">
            <img src="imágenes/portadas/<?= htmlspecialchars($libro["imagen"], ENT_QUOTES, "UTF-8") ?>"
                 alt="Portada de <?= htmlspecialchars($libro["titulo"], ENT_QUOTES, "UTF-8") ?>">
          </a>

          <div class="libro-destacado-info">
            <h3><?= htmlspecialchars($libro["titulo"], ENT_QUOTES, "UTF-8") ?></h3>
            <p><?= htmlspecialchars($libro["autor"], ENT_QUOTES, "UTF-8") ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="home-descubre" aria-labelledby="descubre-title">

  <div class="funciones-contenedor">

    <div class="home-seccion-cabecera">
      <h2 id="descubre-title">Todo lo que puedes hacer en Bookify</h2>
    </div>

    <div class="funciones-lista">
      <article class="funcion-item"><span class="funcion-icono">📚</span><div><h3>Explora</h3><p>Consulta nuestro catálogo y descubre nuevos libros.</p></div></article>
      <article class="funcion-item"><span class="funcion-icono">🔖</span><div><h3>Reserva</h3><p>Reserva libros disponibles y recógelos físicamente en la biblioteca.</p></div></article>
      <article class="funcion-item"><span class="funcion-icono">👤</span><div><h3>Tu biblioteca</h3><p>Consulta tus préstamos y tu historial desde tu cuenta.</p></div></article>
    </div>
  </div>
  </section>

</main>

<?php
require_once("footer.php");
?>
