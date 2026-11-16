$(document).ready(function() {
    'use strict';

    if ($('#tblSedes').length && !$.fn.DataTable.isDataTable('#tblSedes')) {
        $('#tblSedes').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $(document).on('click', '.btn-editar-sede', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#edit_sede_empresa_id').val(btn.data('empresa'));
        $('#edit_sede_nombre').val(btn.data('nombre'));
        $('#formEditarSede').attr('action', `/sedes/${id}`);
    });

    $(document).on('click', '.btn-eliminar-sede', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#deleteSedeNombre').text(btn.data('nombre'));
        $('#formEliminarSede').attr('action', `/sedes/${id}`);
    });
});
