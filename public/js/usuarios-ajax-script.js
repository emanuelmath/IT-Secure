$(document).ready(function() {
    const modalUsuario = new bootstrap.Modal(document.getElementById('modalUsuario'));

    $('#btnNuevoUsuario').on('click', function() {
    $('#formUsuario')[0].reset();
    $('#modalUsuarioLabel').html('<i class="bi bi-person-plus-fill text-cyan me-2"></i> Registrar Usuario');
    modalUsuario.show();
});
});
