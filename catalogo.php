<?php

require_once("db.php");

$localizacion = "catalogo";

$libros = $bbdd->obtenerLibros();

function escaparCatalogo(?string $valor): string
{
    return htmlspecialchars($valor ?? "", ENT_QUOTES, "UTF-8");
}

require_once("cabecera.php");

?>

<main class="catalogo catalogo-pagina" id="catalogo" aria-labelledby="catalogo-title">

  <div class="seccion-cabecera">
    <div>
      <p class="eyebrow">Colección Bookify</p>
      <h2 id="catalogo-title">Catálogo</h2>
    </div>

    <p class="contador">
      <?= count($libros) ?>
      <?= count($libros) === 1 ? "título" : "títulos" ?>
      para descubrir
    </p>
  </div>

  <section id="filtros" aria-labelledby="filtros-title">


    <form id="form-filtros">

      <label>
        <span>Categoría</span>

        <select name="categoria" id="filtro-categoria">
          <option value="">Todas</option>

          <?php
          $categorias = array_values(
              array_unique(
                  array_column($libros, "categoria")
              )
          );

          sort($categorias, SORT_LOCALE_STRING);
          ?>

          <?php foreach ($categorias as $categoria): ?>
            <option value="<?= escaparCatalogo($categoria) ?>">
              <?= escaparCatalogo($categoria) ?>
            </option>
          <?php endforeach; ?>

        </select>
      </label>

      <label>
        <span>Ordenar por</span>

        <select name="orden" id="filtro-orden">
          <option value="">Recomendados</option>
          <option value="titulo_asc">Título: A-Z</option>
          <option value="titulo_desc">Título: Z-A</option>
        </select>
      </label>

    </form>

  </section>

  <div id="productos">

    <?php foreach ($libros as $indice => $libro): ?>
      
      <?php $disponible = $bbdd->comprobarDisponibilidadLibro($libro["id"]); ?>

      <article
        class="libro"
        data-categoria="<?= escaparCatalogo($libro["categoria"]) ?>"
        data-titulo="<?= escaparCatalogo($libro["titulo"]) ?>"
      >

       <a
  class="portada portada-<?= $indice % 4 ?>"
  href="libro.php?id=<?= (int) $libro["id"] ?>"
  aria-label="Ver <?= escaparCatalogo($libro["titulo"]) ?>"
>
  <img
    src="imágenes/portadas/<?= escaparCatalogo($libro["imagen"]) ?>"
    alt="Portada de <?= escaparCatalogo($libro["titulo"]) ?>"
  >
</a>
          <span class="portada-marcapaginas"></span>

        <div class="libro-contenido">

          <p class="libro-categoria">
            <?= escaparCatalogo($libro["categoria"]) ?>
          </p>

          <h3>
            <?= escaparCatalogo($libro["titulo"]) ?>
          </h3>

          <p class="libro-autor">
            <?= escaparCatalogo($libro["autor"]) ?>
          </p>

          <div class="libro-pie">

            <?php if ($disponible): ?>

                <span class="estado-disponible">
                    <i></i> Disponible
                </span>

            <?php else: ?>

                <span class="estado-no-disponible">
                    <i></i> No disponible
                </span>

            <?php endif; ?>

        </div>

        </div>

      </article>

    <?php endforeach; ?>

  </div>

  <p class="sin-resultados" id="sin-resultados" hidden>
    No hay libros que coincidan con estos filtros.
  </p>

</main>



<script src="js/catalogo.js"></script>

<?php
require_once("footer.php");
?>
