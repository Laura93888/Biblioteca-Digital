const iniciarCatalogo = () => {
  const parametros = new URLSearchParams(window.location.search);
  const formFiltros = document.querySelector("#form-filtros");
  const productos = document.querySelector("#productos");
  const contador = document.querySelector(".contador");
  const sinResultados = document.querySelector("#sin-resultados");

  if (!formFiltros || !productos || !contador || !sinResultados) return;

  const selectorCategoria = formFiltros.elements.categoria;
  const selectorOrden = formFiltros.elements.orden;

  function aplicarFiltros() {
    const categoria = selectorCategoria.value;
    const orden = selectorOrden.value;
    const libros = Array.from(productos.querySelectorAll(".libro"));
    const visibles = libros.filter((libro) => {
      return !categoria || libro.dataset.categoria === categoria;
    });

    visibles.sort((libroA, libroB) => {
      if (!orden) return 0;

      const diferencia = libroA.dataset.titulo.localeCompare(
        libroB.dataset.titulo,
        "es",
        { sensitivity: "base" }
      );

      return orden === "titulo_desc" ? -diferencia : diferencia;
    });

    libros.forEach((libro) => {
      libro.hidden = !visibles.includes(libro);
    });

    visibles.forEach((libro) => productos.appendChild(libro));
    contador.textContent = `${visibles.length} ${visibles.length === 1 ? "título" : "títulos"} para descubrir`;
    sinResultados.hidden = visibles.length !== 0;
  }

  selectorCategoria.value = parametros.get("categoria") || "";
  selectorOrden.value = parametros.get("orden") || "";

  formFiltros.addEventListener("change", aplicarFiltros);
  formFiltros.addEventListener("submit", (evento) => evento.preventDefault());
  aplicarFiltros();
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", iniciarCatalogo);
} else {
  iniciarCatalogo();
}
