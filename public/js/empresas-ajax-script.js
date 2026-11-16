$(document).ready(function() {
    'use strict';

    if ($('#tblEmpresas').length && !$.fn.DataTable.isDataTable('#tblEmpresas')) {
        $('#tblEmpresas').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $(document).on('click', '.btn-editar-empresa', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#edit_empresa_nombre').val(btn.data('nombre'));
        $('#edit_empresa_nit').val(btn.data('nit'));
        $('#formEditarEmpresa').attr('action', `/empresas/${id}`);
    });

    $(document).on('click', '.btn-eliminar-empresa', function() {
        const btn = $(this);
        const id = btn.data('id');

        $('#deleteEmpresaNombre').text(btn.data('nombre'));
        $('#formEliminarEmpresa').attr('action', `/empresas/${id}`);
    });
});
