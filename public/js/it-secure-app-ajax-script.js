$(document).ready(function () {
    $(document).on('click', '.dc-toggle-password', function () {
        const $btn = $(this);
        const $input = $($btn.data('target'));
        const $icon = $btn.find('i');

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $icon.removeClass('bi-eye').addClass('bi-eye-slash');
            $btn.addClass('text-cyan');
        } else {
            $input.attr('type', 'password');
            $icon.removeClass('bi-eye-slash').addClass('bi-eye');
            $btn.removeClass('text-cyan');
        }
    });
});
