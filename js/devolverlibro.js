console.log("devolverlibro.js se ha cargado");
const iniciarDevolucion = () => {

    const botonesAbrir = document.querySelectorAll(".boton-devolucion");
    const modal = document.querySelector("#modal-devolucion");
    const botonCerrar = document.querySelector("#cerrar-devolucion");
    const botonCancelar = document.querySelector("#cancelar-devolucion");
    const fondo = document.querySelector("#cerrar-devolucion-fondo");
    const inputIdPrestamo = document.querySelector("#id-prestamo-devolucion");

    if (!botonesAbrir.length || !modal) return;


    function abrirModal(idPrestamo) {

        inputIdPrestamo.value = idPrestamo;

        modal.hidden = false;

        document.body.classList.add("modal-abierto");
    }


    function cerrarModal() {

        modal.hidden = true;

        document.body.classList.remove("modal-abierto");
    }


    botonesAbrir.forEach((boton) => {

        boton.addEventListener("click", () => {

            const idPrestamo = boton.dataset.idPrestamo;

            abrirModal(idPrestamo);

        });

    });


    botonCerrar.addEventListener("click", cerrarModal);

    botonCancelar.addEventListener("click", cerrarModal);

    fondo.addEventListener("click", cerrarModal);


    document.addEventListener("keydown", (evento) => {

        if (evento.key === "Escape" && !modal.hidden) {

            cerrarModal();

        }

    });

};


if (document.readyState === "loading") {

    document.addEventListener(
        "DOMContentLoaded",
        iniciarDevolucion
    );

} else {

    iniciarDevolucion();

}