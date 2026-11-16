$(document).ready(function() {
    'use strict';

    if ($('#tblMantenimientos').length && !$.fn.DataTable.isDataTable('#tblMantenimientos')) {
        $('#tblMantenimientos').DataTable({
            order: [[0, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $(document).on('click', '.btn-ver-mantenimiento', function() {
        const btn = $(this);
        $('#det_equipo').text(btn.data('equipo'));
        $('#det_centro').text(btn.data('centro'));
        $('#det_fecha').text(btn.data('fecha'));
        $('#det_tipo').text(btn.data('tipo'));
        $('#det_tecnico').text(btn.data('tecnico'));
        $('#det_piezas').text(btn.data('piezas'));
        $('#det_descripcion').text(btn.data('descripcion'));
    });
});
