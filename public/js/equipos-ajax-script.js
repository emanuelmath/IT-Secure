document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    const modalEditar = document.getElementById('modalEditarEquipo');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            if (!btn) return;

            const id = btn.getAttribute('data-id');
            const form = document.getElementById('formEditarEquipo');
            const urlBase = form.getAttribute('data-action-template');

            document.getElementById('editTitleCode').textContent = btn.getAttribute('data-codigo') || '';
            document.getElementById('edit_codigo_inventario').value = btn.getAttribute('data-codigo') || '';
            document.getElementById('edit_tipo').value = btn.getAttribute('data-tipo') || '';
            document.getElementById('edit_marca').value = btn.getAttribute('data-marca') || '';
            document.getElementById('edit_modelo').value = btn.getAttribute('data-modelo') || '';
            document.getElementById('edit_numero_serie').value = btn.getAttribute('data-serie') || '';
            document.getElementById('edit_estado').value = btn.getAttribute('data-estado') || '';
            document.getElementById('edit_dias_frecuencia_mantenimiento').value = btn.getAttribute('data-frecuencia') || '';
            document.getElementById('edit_centros_de_computo_id').value = btn.getAttribute('data-centro') || '';

            if (urlBase && id) {
                form.setAttribute('action', urlBase.replace('__ID__', id));
            }
        });
    }

    const modalEliminar = document.getElementById('modalEliminarEquipo');
    if (modalEliminar) {
        modalEliminar.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            if (!btn) return;

            const id = btn.getAttribute('data-id');
            const form = document.getElementById('formEliminarEquipo');
            const urlBase = form.getAttribute('data-action-template');

            document.getElementById('deleteCode').textContent = btn.getAttribute('data-codigo') || '';

            if (urlBase && id) {
                form.setAttribute('action', urlBase.replace('__ID__', id));
            }
        });
    }

    const modalQr = document.getElementById('modalQrEquipo');
    if (modalQr) {
        modalQr.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            if (!btn) return;

            const codigo = btn.getAttribute('data-codigo') || '';
            const tipo = btn.getAttribute('data-tipo') || '';
            const marca = btn.getAttribute('data-marca') || '';
            const modelo = btn.getAttribute('data-modelo') || '';
            const serie = btn.getAttribute('data-serie') || 'S/N';
            const centro = btn.getAttribute('data-centro') || '';
            const sede = btn.getAttribute('data-sede') || '';
            const urlFicha = btn.getAttribute('data-url') || window.location.href;

            document.getElementById('qr_codigo').textContent = codigo;
            document.getElementById('qr_equipo').textContent = `${tipo} - ${marca} ${modelo}`;
            document.getElementById('qr_serie').textContent = `S/N: ${serie}`;
            document.getElementById('qr_ubicacion').textContent = `${centro} (${sede})`;

            const container = document.getElementById('qrCanvasContainer');
            if (container) {
                container.innerHTML = '';
                if (typeof QRCode !== 'undefined') {
                    new QRCode(container, {
                        text: urlFicha,
                        width: 105,
                        height: 105,
                        colorDark: "#083885",
                        colorLight: "#f6f6f8",
                        correctLevel: QRCode.CorrectLevel.M
                    });
                }
            }
        });
    }
});
