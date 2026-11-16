$(document).ready(function() {
    'use strict';

    if ($('#tblTraslados').length && !$.fn.DataTable.isDataTable('#tblTraslados')) {
        $('#tblTraslados').DataTable({
            order: [[0, 'desc']],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json"
            }
        });
    }

    $('#selectEquipoTraslado').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const origenId = selectedOption.data('origen-id');
        const origenNombre = selectedOption.data('origen-nombre');

        $('#hiddenCcOrigenId').val(origenId);
        $('#displayCentroOrigen').val(origenNombre);

        $('#selectCentroDestino option').prop('disabled', false);
        if (origenId) {
            $(`#selectCentroDestino option[value="${origenId}"]`).prop('disabled', true);
            if ($('#selectCentroDestino').val() == origenId) {
                $('#selectCentroDestino').val('');
            }
        }
    });

    $(document).on('click', '.btn-ver-traslado', function() {
        const btn = $(this);
        $('#det_traslado_equipo').text(btn.data('equipo'));
        $('#det_traslado_fecha').text(btn.data('fecha'));
        $('#det_traslado_origen').text(btn.data('origen'));
        $('#det_traslado_destino').text(btn.data('destino'));
        $('#det_traslado_autorizado').text(btn.data('autorizado'));
        $('#det_traslado_motivo').text(btn.data('motivo'));
    });
});
