document.addEventListener('DOMContentLoaded', function () {
    const filtroNombre = document.getElementById('filtroNombre');
    const filtroFecha = document.getElementById('filtroFecha');
    const tablaReservas = document.getElementById('tablaReservas');
    const borrarFiltros = document.getElementById('borrarFiltros');

    filtroNombre.addEventListener('input', filtrarTabla);
    filtroFecha.addEventListener('input', filtrarTabla);

    borrarFiltros.addEventListener('click', function() {
        filtroNombre.value = '';
        filtroFecha.value = '';
        filtrarTabla();
        borrarFiltros.style.display = 'none';
    });

    function filtrarTabla() {
        const nombreFiltro = filtroNombre.value.toLowerCase();
        const fechaFiltro = filtroFecha.value;
        const filas = tablaReservas.getElementsByTagName('tr');

        let filtrosActivos = false;

        for (let fila of filas) {
            const nombre = fila.cells[0].textContent.toLowerCase();
            const fecha = fila.cells[5].textContent;

            const coincideNombre = nombre.includes(nombreFiltro);
            const coincideFecha = fechaFiltro === '' || fecha === fechaFiltro;

            fila.style.display = (coincideNombre && coincideFecha) ? '' : 'none';

            if (nombreFiltro || fechaFiltro) filtrosActivos = true;
        }

        borrarFiltros.style.display = filtrosActivos ? 'block' : 'none';
    }
});