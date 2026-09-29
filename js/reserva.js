const iniciarReserva = () => {

    const botonAbrir = document.querySelector("#abrir-reserva");
    const modal = document.querySelector("#modal-reserva");
    const botonCerrar = document.querySelector("#cerrar-reserva");
    const botonCancelar = document.querySelector("#cancelar-reserva");
    const fondo = document.querySelector("#cerrar-reserva-fondo");

    if (!botonAbrir || !modal) return;


    function abrirModal() {
        modal.hidden = false;
        document.body.classList.add("modal-abierto");
    }


    function cerrarModal() {
        modal.hidden = true;
        document.body.classList.remove("modal-abierto");
    }


    botonAbrir.addEventListener("click", abrirModal);

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
        iniciarReserva
    );

} else {

    iniciarReserva();

}