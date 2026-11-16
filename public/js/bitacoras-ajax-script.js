$(document).ready(function() {
    'use strict';

    if ($('#tblBitacoras').length && !$.fn.DataTable.isDataTable('#tblBitacoras')) {
        $('#tblBitacoras').DataTable({
            order: [[0, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $(document).on('click', '.btn-ver-bitacora', function() {
        const btn = $(this);
        $('#log_usuario').text(btn.data('usuario'));
        $('#log_fecha').text(btn.data('fecha'));
        $('#log_modulo').text(btn.data('modulo'));
        $('#log_evento').text(btn.data('evento'));
        $('#log_ip').text(btn.data('ip'));
        $('#log_registro').text(btn.data('registro'));
        $('#log_descripcion').text(btn.data('descripcion'));
    });
});
