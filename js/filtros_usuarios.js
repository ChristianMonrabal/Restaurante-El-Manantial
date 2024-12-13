document.addEventListener('DOMContentLoaded', function () {
    const filtroUsuario = document.getElementById('filtroUsuario');
    const filtroPuesto = document.getElementById('filtroPuesto');
    const tablaUsuarios = document.getElementById('tablaUsuarios');
    const borrarFiltros = document.getElementById('borrarFiltros');

    filtroUsuario.addEventListener('input', filtrarTabla);
    filtroPuesto.addEventListener('change', filtrarTabla);

    borrarFiltros.addEventListener('click', function() {
        filtroUsuario.value = '';
        filtroPuesto.value = '';
        filtrarTabla();
        borrarFiltros.style.display = 'none';
    });

    function filtrarTabla() {
        const usuarioFiltro = filtroUsuario.value.toLowerCase();
        const puestoFiltro = filtroPuesto.value.toLowerCase();
        const filas = tablaUsuarios.getElementsByTagName('tr');

        let filtrosActivos = false;

        for (let fila of filas) {
            const usuario = fila.cells[0].textContent.toLowerCase();
            const puesto = fila.cells[2].textContent.toLowerCase();

            const coincideUsuario = usuario.includes(usuarioFiltro);
            const coincidePuesto = puestoFiltro === '' || puesto === puestoFiltro;

            fila.style.display = (coincideUsuario && coincidePuesto) ? '' : 'none';

            if (usuarioFiltro || puestoFiltro) filtrosActivos = true;
        }

        borrarFiltros.style.display = filtrosActivos ? 'block' : 'none';
    }
});
