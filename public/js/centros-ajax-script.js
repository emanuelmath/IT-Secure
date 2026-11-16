$(document).ready(function() {
    'use strict';

    if ($('#tblCentros').length && !$.fn.DataTable.isDataTable('#tblCentros')) {
        $('#tblCentros').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $(document).on('click', '.btn-editar-centro', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#edit_centro_sede_id').val(btn.data('sede'));
        $('#edit_centro_codigo').val(btn.data('codigo'));
        $('#edit_centro_nombre').val(btn.data('nombre'));
        $('#formEditarCentro').attr('action', `/centros-computo/${id}`);
    });

    $(document).on('click', '.btn-eliminar-centro', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#deleteCentroNombre').text(btn.data('nombre'));
        $('#deleteCentroCodigo').text(btn.data('codigo'));
        $('#formEliminarCentro').attr('action', `/centros-computo/${id}`);
    });
});
