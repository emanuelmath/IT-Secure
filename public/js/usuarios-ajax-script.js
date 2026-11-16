$(document).ready(function () {
    'use strict';

    $('#btnNuevoUsuario').on('click', function () {
        $('#formUsuario')[0].reset();
        $('#roles').val([]);
        $('#centros').val([]);
        $('#modalUsuarioLabel').html('<i class="bi bi-person-plus-fill text-cyan me-2 opacity-75"></i> Registrar Usuario');
    });
});

window.editarUsuario = function (id) {
    const $form = $('#formEditar');
    const urlBase = $form.data('action-template');

    $.ajax({
        url: `/usuarios/${id}/buscar`,
        method: 'GET',
        dataType: 'json'
    })
        .done(function (usuario) {
            $form[0].reset();
            $form.find('input[type="checkbox"]').prop('checked', false);

            $('#editar_nombre').val(usuario.nombre ?? '');
            $('#editar_email').val(usuario.email ?? '');

            if (Array.isArray(usuario.roles)) {
                usuario.roles.forEach(function (rol) {
                    $form.find(`input[name="roles[]"][value="${rol.id}"]`).prop('checked', true);
                });
            }

            if (Array.isArray(usuario.centros_de_computo)) {
                usuario.centros_de_computo.forEach(function (cc) {
                    $form.find(`input[name="centros[]"][value="${cc.id}"]`).prop('checked', true);
                });
            }

            $form.attr('action', urlBase.replace('__ID__', usuario.id));
        })
        .fail(function () {
            alert('Error de conexión al consultar el usuario.');
        });
};

window.eliminarUsuario = function (id, nombre) {
    const $form = $('#formEliminar');
    const urlBase = $form.data('action-template');

    $('#nombreEliminar').text(nombre);
    $form.attr('action', urlBase.replace('__ID__', id));
};

window.activarUsuario = function (id, nombre) {
    const $form = $('#formActivar');
    const urlBase = $form.data('action-template');

    $('#nombreActivar').text(nombre);
    $form.attr('action', urlBase.replace('__ID__', id));
};
